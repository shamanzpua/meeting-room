<?php

namespace common\overrides\db;

use yii\helpers\ArrayHelper;

/**
 * Extend the ActiveRecord
 *
 * @author user
 */
class ActiveRecord extends \yii\db\ActiveRecord
{
    
    /**
     * Set scenario for nested relates
     *
     * @inheritdoc
     */
    public function toArray(array $fields = [], array $expand = [], $recursive = true)
    {
        $data = [];
        foreach ($this->resolveFields($fields, $expand) as $field => $definition) {
            $data[$field] = is_string($definition) ? $this->$definition : call_user_func($definition, $this, $field);
            
            // Customized here:
            if ($data[$field] instanceof \yii\db\ActiveRecord) {
                $data[$field]->setScenario($this->scenario);
            }
        }

        if ($this instanceof Linkable) {
            $data['_links'] = Link::serialize($this->getLinks());
        }

        return $recursive ? ArrayHelper::toArray($data) : $data;
    }
    
    public static function getActiveQuery()
    {
        return static::find();
    }
    
    public static function getById($id)
    {
        return static::find()->where(['id' => $id])->one();
    }
    
    public static function getForSelect()
    {
        return ArrayHelper::map(static::getActiveQuery()->indexBy('id')->select('id, name')->asArray()->all(), 'id', 'name');
    }
    
//    return ArrayHelper::map(self::find()->indexBy('id')->select('id, name')->asArray()->all(), 'id', 'name');
}
