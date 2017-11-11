<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace api\versions\v1\controllers\user;

use common\models\base\LoginForm;
use Yii;
use yii\web\ServerErrorHttpException;
use common\models\token\UserToken;

/**
 * This action implemented to demonstrate the receipt of the token.
 * Do not use it on production systems.
 * @return string AuthKey or model with errors
 */
class LoginAction extends \yii\rest\CreateAction
{
    /**
     * Field name of body parameter for remember
     * @var string
     */
    public $rememberField = 'remember';
    
    /**
     * Confirm value of body parameter for remember
     * @var array
     */
    public $rememberYesValue = 'yes';
    
    /**
     * Confirm value of body parameter for not remember
     * @var array
     */
    public $rememberNoValue = 'no';
    
    /**
     * Field name of body parameter for verify_ip
     * @var string
     */
    public $verifyIpField = 'verify_ip';
    
    /**
     * Confirm value of body parameter for verify_ip
     * @var array
     */
    public $verifyIpYesValue = 'yes';
    
    
    /**
     * If remember me expire time as seconds
     * @var integer
     */
    public $expireSecondsRemember = 604800; // One week
    /**
     * If not remember me expire time as seconds
     * @var integer
     */
    public $expireSecondsNotRemember = 3600; // One hour
    
    /**
     * Creates a new model.
     * @return \yii\db\ActiveRecordInterface the model newly created
     * @throws ServerErrorHttpException if there is any error when creating the model
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var $model LoginForm */
        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);

        /** @var $request \yii\web\Request */
        $request = Yii::$app->getRequest();
        
        $model->setAttributes($request->getBodyParams());
        $model->device_token = $request->headers->get('x-device-token');
        $model->device_type = $request->headers->get('x-device-type');
        if ($model->login($model)) {
            /* @var $userToken \common\models\token\UserToken */
            $userToken = UserToken::createForUser(Yii::$app->user->identity, $this->expireSecondsRemember);
            
            if (!$userToken) {
                throw new ServerErrorHttpException('Failed to create the object for user token.');
            }

            return $userToken;
            
        } elseif (!$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed login.');
        }
        
        return $model;
    }
}
