<?php

return [
    'components' => [
        'request' => [
            'baseUrl' => '/api',
        ],
        'response' => [
            'formatters' => [
                \yii\web\Response::FORMAT_JSON => '\yii\web\JsonResponseFormatter',
            ],
        ],
        'log' => [
            'traceLevel' => 3,
        ],
    ],
];
