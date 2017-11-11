<?php

namespace common\components\push;

use common\models\meeting\Meeting;
use common\models\token\UserToken;
use Yii;

class AndroidPushProvider implements PushProviderInterface
{

    /**
     * @param $message
     * @return mixed
     */
    public function send($message)
    {
        Yii::$app->gcm->sendMulti(UserToken::getByDeviceTokens(UserToken::DEVICE_TYPE_ANDROID), $message);
    }
}
