<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\overrides\web;

use DOMDocument;
use DOMElement;
use DOMText;
use yii\base\Arrayable;
use yii\helpers\StringHelper;

/**
 * XmlResponseFormatter formats the given data into an XML response content.
 *
 * It is used by [[Response]] to format response data.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class XmlResponseFormatter extends \yii\web\XmlResponseFormatter
{
    /**
     * Formats the specified response.
     * @param Response $response the response to be formatted.
     */
    public function format($response)
    {
        $charset = $this->encoding === null ? $response->charset : $this->encoding;
        if (stripos($this->contentType, 'charset') === false) {
            $this->contentType .= '; charset=' . $charset;
        }
        $response->getHeaders()->set('Content-Type', $this->contentType);
        if ($response->data !== null) {
            $dom = new DOMDocument($this->version, $charset);
            $root = new DOMElement($this->rootTag);
            $result = new DOMElement($this->itemTag);
            $code = new DOMElement($this->itemTag);
            $status = new DOMElement($this->itemTag);
            $dom->appendChild($root);
            
            $code->appendChild($response->getStatusCode());
            $status->appendChild(($response->getIsSuccessful())? 'success' : 'error');

            $messageText = '';
            if (!$response->getIsOk()) {
                $messageText = $response->statusText;
            }

            if (is_string($response->data)) {
                //For string result we send it like 'message'
                $messageText = $response->data;
            } elseif ($response->getIsClientError() && isset($response->data['message'])) {
                //For HttpExceptions we save message field only to 'message'
                $messageText = $response->data['message'];
                unset($response->data['message']);
                $result->appendChild($response->data);
            } else {
                //Otherwise send all as result
                $result->appendChild($response->data);
            }
            
            if ($messageText !== '') {
                $message = new DOMElement($this->itemTag);
                $message->appendChild($messageText);
                $root->appendChild($message);
            }
            $root->appendChild($result);
            $root->appendChild($code);
            $root->appendChild($status);
            $this->buildXml($root, $response->data);
            $response->content = $dom->saveXML();
        }
    }
}
