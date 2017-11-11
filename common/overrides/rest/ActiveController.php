<?php

namespace common\overrides\rest;

use yii\helpers\ArrayHelper;
use yii\web\Response;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use common\overrides\filter\auth\HeaderParamAuth;

class ActiveController extends \yii\rest\ActiveController
{
    const ACTION_CREATE = 'create';
    const ACTION_INDEX = 'index';
    const ACTION_UPDATE = 'update';
    const ACTION_VIEW = 'view';
    const ACTION_DELETE = 'delete';

    /**
     * @inheritdoc
     */
    public $serializer = ['class' => Serializer::class];

    /**
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            self::getDefaultBehaviors()
        );
    }

    /**
     * @return array
     */
    public static function getDefaultBehaviors()
    {
        return [
            'authenticator' => [
                'class' => CompositeAuth::class,
                'authMethods' => [
                    HeaderParamAuth::class,
                    HttpBasicAuth::class,
                    HttpBearerAuth::class,
                    QueryParamAuth::class,
                ],
                'except' => ['options'],
            ],
            'contentNegotiator' => [
                'class' => 'yii\filters\ContentNegotiator',
                'formats' => [
                    'text/html' => Response::FORMAT_JSON,
                    'application/json' => Response::FORMAT_JSON,
                    'application/xml' => Response::FORMAT_XML,
                ],
            ],
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['*'],
                    'Access-Control-Request-Method' => ['GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'HEAD', 'OPTIONS'],
                    'Access-Control-Request-Headers' => ['*'],
                    'Access-Control-Max-Age' => 86400,
                    'Access-Control-Allow-Credentials' => true,
                    'Access-Control-Allow-Headers' => ['Content-Type', 'Authorization'],
                ],
            ],
            'access' => [
                'except' => ['options'],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    protected function verbs()
    {
        $verbs = parent::verbs();
        $verbs['update'] = ['POST'];
        return $verbs;
    }
}
