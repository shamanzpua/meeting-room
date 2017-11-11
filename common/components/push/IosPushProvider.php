<?php
namespace common\components\push;

use bryglen\apnsgcm\Apns;
use common\models\token\UserToken;
use Yii;

/**
 * Class IosPushProvider
 * @package common\components\push
 */
class IosPushProvider implements PushProviderInterface
{
    /**
     * @param $message
     * @return mixed
     */
    public function send($message)
    {
        Yii::$app->apns->sendMulti(UserToken::getByDeviceTokens(UserToken::DEVICE_TYPE_IOS), $message);
    }
}
