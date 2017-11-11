<?php

namespace modules\task;
use Yii;

/**
 * Class Module
 * @package modules\task
 */
class Module extends \yii\base\Module
{
    public function init()
    {
        if ($this->controllerNamespace === null) {
            $class = get_class($this);
            if (($pos = strrpos(
                    $class,
                    '\\'
                )) !== false
            ) {
                $this->controllerNamespace = substr(
                        $class,
                        0,
                        $pos
                    ) . '\\controllers\\' . Yii::$app->id;
            }
        }
        parent::init();
    }
}
