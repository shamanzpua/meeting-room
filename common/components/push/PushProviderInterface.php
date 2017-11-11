<?php
namespace common\components\push;

/**
 * Interface PushProviderInterface
 * @package common\components\push
 */
interface PushProviderInterface
{
    /**
     * @param $message
     * @return mixed
     */
    public function send($message);
}
