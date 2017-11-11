<?php

namespace api\versions\v1\controllers\user;

use common\models\base\SignupForm;
use Yii;
use yii\helpers\Url;
use yii\web\ServerErrorHttpException;

/**
 * Class RegistrationAction
 * @package api\versions\v1\controllers\user
 */
class RegistrationAction extends \yii\rest\CreateAction
{
    /**
     * @return mixed
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /* @var SignupForm $model */
        $model = new $this->modelClass([
            'scenario' => $this->scenario,
        ]);

        $model->setAttributes(Yii::$app->getRequest()->getBodyParams());

        $user = $model->signup($model);
        if ($model->hasErrors()) {
            return $model;
        }

        Yii::$app->response->setStatusCode(201);
        return $user;
    }
}
