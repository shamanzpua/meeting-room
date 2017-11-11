<?php

return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'modules' => [
        'task' => [
            'class' => 'modules\task\Module',
        ],
    ],
    'components' => [
        'push' => [
            'class' => 'common\components\push\PushService',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'redis' => [
            'class' => '\yii\redis\Connection',
            'hostname' => 'redis',
            'port' => 6379,
            'database' => 0,
        ],
        'mongodb' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://@mongo:27017/test_db',
            'options' => [
                "username" => getenv('MONGO_USER'),
                "password" => getenv('MONGO_PASSWORD')
            ]
        ],
        'apns' => [
            'class' => 'bryglen\apnsgcm\Apns',
            'environment' => \bryglen\apnsgcm\Apns::ENVIRONMENT_SANDBOX,
            'pemFile' => dirname(__FILE__).'/apns/'. getenv('APPLE_APNS_CERTIFICATE'),
            'options' => [
                'sendRetryTimes' => 5
            ]
        ],
        'gcm' => [
            'class' => 'bryglen\apnsgcm\Gcm',
            'apiKey' => getenv('GCM_API_KEY'),
        ],
        'apnsGcm' => [
            'class' => 'bryglen\apnsgcm\ApnsGcm',
        ],
        'queue' => [
            'class' => \UrbanIndo\Yii2\Queue\Queues\RedisQueue::class,
            'waitSecondsIfNoQueue' => 10,
            'module' => 'task'
        ]
    ],
];
