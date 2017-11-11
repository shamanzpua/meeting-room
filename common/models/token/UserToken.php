<?php

namespace common\models\token;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\mongodb\ActiveRecord;
use yii\web\IdentityInterface;
use common\models\User;

/**
 * This is the model class for table "user_token".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $token
 * @property integer $verify_ip
 * @property string $user_ip
 * @property boolean $frozen_expire
 * @property string $user_agent
 * @property string $created_at
 * @property string $expired_at
 *
 * @property User $user
 */
class UserToken extends ActiveRecord
{
    const SCENARIO_CREATE_FOR_USER = 'createForUser';
    
    /**
     * Default life time for user token
     */
    const EXPIRE_DEFAULT_SECONDS = 604800; // One week

    const DEVICE_TYPE_IOS = 'ios';
    const DEVICE_TYPE_ANDROID = 'android';

    /**
     * @return array
     */
    public function attributes()
    {
        return [
            '_id',
            'user_id',
            'token',
            'created_at',
            'expired_at',
            'user_ip',
            'device_token',
            'device_type'
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'token'], 'required', 'on' => self::SCENARIO_DEFAULT],
            [['token'], 'required', 'on' => self::SCENARIO_CREATE_FOR_USER],
            [['user_id'], 'safe'],
            [['device_token'], 'string'],
            [['device_type'], 'in', 'range' => [static::DEVICE_TYPE_ANDROID, static::DEVICE_TYPE_IOS]],
            [['created_at', 'expired_at'], 'safe'],
            [['token'], 'string', 'max' => 128],
            [['user_ip'], 'string', 'max' => 46],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function fields()
    {
        $fields = [
            'token',
            'expired_at',
            'device_token',
            'user',

        ];
        
        return $fields;
    }
    
    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return \yii\helpers\ArrayHelper::merge(parent::scenarios(), [
            self::SCENARIO_CREATE_FOR_USER => [],
        ]);
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'token' => 'Token',
            'verify_ip' => 'Verify IP',
            'user_ip' => 'User IP',
            'frozen_expire' => 'Frozen Expire',
            'user_agent' => 'User Agent',
            'created_at' => 'Created At',
            'expired_at' => 'Expired At',
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    /**
     * @param $type
     * @return array
     */
    public static function getByDeviceTokens($type)
    {
        return static::find()
            ->select(['device_token'])
            ->andWhere(['device_type' => $type])
            ->andFilterCompare('expired_at', time(), '$gte')
            ->column();
    }

    /**
     * @return static
     */
    public function getUser()
    {
        return User::find()->andWhere(['_id' => $this->user_id])->one();
    }
    
    /**
     * @param IdentityInterface $user
     * @param int $seconds
     * @param bool $remember
     * @param bool $verifyIP
     * @return bool|UserToken
     */
    public static function createForUser(IdentityInterface $user, $seconds = self::EXPIRE_DEFAULT_SECONDS, $remember = false, $verifyIP = false)
    {
        $deviceToken = Yii::$app->request->headers->get('x-device-token');

        $userToken = static::find()->andWhere(['device_token' => $deviceToken])->one();

        if (!$userToken) {
            $userToken = new self(['scenario' => self::SCENARIO_CREATE_FOR_USER]);
            $userToken->user_id = $user->id;
            $userToken->device_token = Yii::$app->request->headers->get('x-device-token');
            $userToken->device_type = Yii::$app->request->headers->get('x-device-type');
        }

        $userToken->token = Yii::$app->getSecurity()->generateRandomString();
        $userToken->expired_at = (time() + $seconds);


        if ($userToken->save()) {

            $userToken->refresh();
            
            return $userToken;
        } else {
            return false;
        }
    }
    
    /**
     * Check token for expired
     * @return boolean
     */
    public function expired()
    {
        $expiredAt = (int) Yii::$app->formatter->asTimestamp($this->expired_at);
        $now = (int) Yii::$app->formatter->asTimestamp(time());
        return $now >= $expiredAt;
    }
}
