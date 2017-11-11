<?php

namespace common\overrides\web;

/**
 * Formats the given data into a JSON or JSONP response content.
 */
class JsonResponseFormatter extends \yii\web\JsonResponseFormatter
{
    /**
     * @inheritdoc
     */
    public function format($response)
    {
        //Resulting data
        $resultData = [
            'code' => $response->getStatusCode(),
            'result' => [],
            'status' => ($response->getIsSuccessful())? 'success' : 'error',
        ];

        if (!$response->getIsOk()) {
            $resultData['message'] = $response->statusText;
        }

        if (is_string($response->data)) {
            //For string result we send it like 'message'
            $resultData['message'] = $response->data;
        } elseif ($response->getIsClientError() && isset($response->data['message'])) {
            //For HttpExceptions we save message field only to 'message'
            $resultData['message'] = $response->data['message'];
            unset($response->data['message']);
            $resultData['result'] = $response->data;
        } else {
            //Otherwise send all as result
            $resultData['result'] = $response->data;
        }

        //Set resulting data to response->data and run parent format function
        $response->data = $resultData;
        parent::format($response);
    }
}
