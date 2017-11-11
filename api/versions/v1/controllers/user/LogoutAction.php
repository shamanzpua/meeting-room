<?php

namespace api\versions\v1\controllers\user;

use Yii;
use yii\base\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class LogoutAction
 * @package api\versions\v1\controllers\user
 */
class LogoutAction extends Action
{

    public function run()
    {
        Yii::$app->user->identity->userToken->delete();
        Yii::$app->response->setStatusCode(204);
    }
}
