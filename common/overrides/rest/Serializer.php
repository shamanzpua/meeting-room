<?php

namespace common\overrides\rest;

/**
 * @inheritdoc
 */
class Serializer extends \yii\rest\Serializer
{
    /**
     * @inheritdoc
     */
    public $collectionEnvelope = 'items';
    
    /**
     * @inheritdoc
     */
    public function serialize($data) {
        $_data = parent::serialize($data);
        
        if (!is_array($_data)) {
            return [];
        }
        
        /**
         * Ignore meta data for collection in response
         */
        if (isset($_data[$this->collectionEnvelope])) {
            return $_data[$this->collectionEnvelope];
        }
        
        return $_data;
    }
}
