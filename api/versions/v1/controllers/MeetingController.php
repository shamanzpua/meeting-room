<?php

namespace api\versions\v1\controllers;

use common\models\meeting\Meeting;
use Yii;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\base\LoginForm;
use common\overrides\rest\ActiveController;
use api\versions\v1\controllers\user\LoginAction;
use api\versions\v1\controllers\user\LogoutAction;

/**
 * Class MeetingController
 * @package api\versions\v1\controllers
 */
class MeetingController extends ActiveController
{
    public $modelClass = Meeting::class;
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'except' => [],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => [],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => [static::ACTION_CREATE, static::ACTION_INDEX],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }
}
