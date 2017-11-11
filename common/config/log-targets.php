<?php

return [
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['error'],
        'categories' => ['dev'],
        'logFile' => '@common/logs/dev/error.log',
    ],
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['warning'],
        'categories' => ['dev'],
        'logFile' => '@common/logs/dev/warning.log',
    ],
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['info'],
        'categories' => ['dev'],
        'logFile' => '@common/logs/dev/info.log',
    ],
    [
        'class' => 'yii\log\FileTarget',
        'levels' => ['trace'],
        'categories' => ['dev'],
        'logFile' => '@common/logs/dev/trace.log',
    ]
];
