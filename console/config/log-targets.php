<?php

return [
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error', 'warning'],
        'logFile' => '@common/logs/console/console.log',
        'except' => [
            'dev',
        ],
    ]
];
