<?php
namespace backend\models\base;

use Yii;
use yii\base\Model;
use common\models\User;
use yii\helpers\ArrayHelper;

/**
 * Login form
 */
class LoginForm extends \common\models\base\LoginForm
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['password', 'validateRole']
            ]
        );
    }

    /**
     * Validates the admin role.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateRole($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->isAdmin) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }
}
