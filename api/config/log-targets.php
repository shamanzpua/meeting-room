<?php

return [
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning'],
        'logFile' => '@common/logs/api/api.log',
        'except' => [
            'dev',
        ],
    ]
];
