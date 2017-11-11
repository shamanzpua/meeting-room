<?php
namespace console\controllers;

use Yii;
use yii\console\Controller as Controller;

class CronController extends Controller
{
    public function actionIndex()
    {
        Yii::trace('cron launched...', 'dev');
    }
}
