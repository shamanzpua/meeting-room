<?php
namespace api\versions\v1\controllers;

use Yii;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use common\models\base\SignupForm;
use common\models\base\LoginForm;
use common\overrides\rest\ActiveController;
use api\versions\v1\models\User;
use api\versions\v1\controllers\user\RegistrationAction;
use api\versions\v1\controllers\user\LoginAction;
use api\versions\v1\controllers\user\LogoutAction;
use yii\rest\IndexAction;

/**
 * Class UserController
 * @package api\versions\v1\controllers
 */
class UserController extends ActiveController
{

    const ACTION_LOGIN = 'login';
    const ACTION_LOGOUT = 'logout';

    public $modelClass = User::class;
    
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return ArrayHelper::merge(parent::behaviors(), [
            'authenticator' => [
                'except' => ['create', 'index', 'login'],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create', 'index', 'login'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['logout'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ]);
    }

    /**
     * @return array
     */
    public function actions()
    {
        return ArrayHelper::merge(parent::actions(), [
            static::ACTION_CREATE => [
                'class' => RegistrationAction::class,
                'modelClass' => SignupForm::class,
            ],
            static::ACTION_LOGIN => [
                'class' => LoginAction::class,
                'modelClass' => LoginForm::class,
            ],
            static::ACTION_LOGOUT => [
                'class' => LogoutAction::class,
            ],
        ]);
    }
}
