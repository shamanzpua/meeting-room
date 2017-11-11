<?php
namespace modules\task\controllers\console;

use common\components\push\AndroidPushProvider;
use common\components\push\IosPushProvider;
use common\components\push\PushService;
use common\models\meeting\Meeting;
use common\models\token\UserToken;
use Yii;
use UrbanIndo\Yii2\Queue\Worker\Controller;

/**
 * Class MeetingController
 * @package console\controllers
 */
class MeetingController extends Controller
{
    /**
     * @param $meetingId
     */
    public function actionSendPush($meetingId)
    {
        /**
         * @var PushService $pushService
         */
        $pushService = Yii::$app->push;
        $meeting = Meeting::find()->andWhere(['_id' => $meetingId])->one();

        $pushService->generateMessage($meeting)
        ->send(new AndroidPushProvider())
        ->send(new IosPushProvider());

    }

}
