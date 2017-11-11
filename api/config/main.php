<?php
$beforeRequest = require(__DIR__ . '/before-request.php');
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);
$log = array_merge(
    require(__DIR__ . '/../../common/config/log-targets.php'),
    require(__DIR__ . '/log-targets.php')
);

return [
    'id' => 'api',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [
            'class' => 'api\versions\v1\RestModule'
        ],
        'task' => [
            'class' => 'modules\task\Module',
        ],
    ],
    'components' => [
        'user' => [
            'identityClass' => 'common\models\User',
            'enableSession' => false,
        ],
        'response' => [
            'format' => yii\web\Response::FORMAT_JSON,
            'charset' => 'UTF-8',
        ],
        'log' => [
            'targets' => $log
        ],
        'request' => [
            'class' => '\yii\web\Request',
            'enableCookieValidation' => false,
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ],
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'enableStrictParsing' => false,
            'showScriptName' => false,
            'rules' => [
                [
                    'class' => 'common\overrides\rest\UrlRule',
                    'controller' => ['v1/user'],
                    'extraPatterns' => [
                        'POST login' => 'login',
                        'POST logout' => 'logout',

                    ],
                    'pluralize' => false,
                ],
                [
                    'class' => 'common\overrides\rest\UrlRule',
                    'controller' => ['v1/meeting'],
                    'extraPatterns' => [

                    ],
                    'pluralize' => false,
                ],
            ],
        ],
    ],
    'on beforeRequest' => $beforeRequest,
    'params' => $params,
];
