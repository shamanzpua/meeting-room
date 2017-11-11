<?php

namespace common\overrides\filter\auth;

use yii\filters\auth\HttpBearerAuth;

class HeaderParamAuth extends HttpBearerAuth
{
    /**
     * @inheritdoc
     */
    public function authenticate($user, $request, $response)
    {
        return parent::authenticate($user, $request, $response);
    }
}
