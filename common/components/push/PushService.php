<?php
namespace common\components\push;

use common\models\meeting\Meeting;
use yii\base\Component;
use Yii;

/**
 * Class PushService
 * @package common\components\push
 */
class PushService extends Component
{
    private $message;

    /**
     * @param Meeting $meeting
     * @return $this
     */
    public function generateMessage(Meeting $meeting)
    {
        $this->message = "Meeting room is occupied from {$meeting->start} to {$meeting->end}";
        return $this;
    }

    /**
     * @param PushProviderInterface $provider
     * @return $this
     */
    public function send(PushProviderInterface $provider)
    {
        try {
            $provider->send($this->message);
        } catch (\Exception $ex) {
            $message = "Error: {$ex->getMessage()}";

            echo $message . "\n";
            Yii::error($message, 'app');
        }

        return $this;
    }
}
