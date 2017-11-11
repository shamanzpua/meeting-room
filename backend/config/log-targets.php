<?php

return [
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning'],
        'logFile' => '@common/logs/backend/backend.log',
        'except' => [
            'dev',
        ],

    ]
];
