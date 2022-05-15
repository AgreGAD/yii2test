<?php

use app\models\types\HistoryEvent;
use app\models\search\HistorySearch;

/** @var $model HistorySearch */

$renderers = [
    HistoryEvent::EVENT_DEFAULT->value => '_event_default',
    HistoryEvent::EVENT_CREATED_TASK->value => '_event_task',
    HistoryEvent::EVENT_COMPLETED_TASK->value => '_event_task',
    HistoryEvent::EVENT_UPDATED_TASK->value => '_event_task',
    HistoryEvent::EVENT_INCOMING_SMS->value => '_event_sms',
    HistoryEvent::EVENT_OUTGOING_SMS->value => '_event_sms',
    HistoryEvent::EVENT_INCOMING_FAX->value => '_event_fax',
    HistoryEvent::EVENT_OUTGOING_FAX->value => '_event_fax',
    HistoryEvent::EVENT_CUSTOMER_CHANGE_TYPE->value => '_event_customer_change_type',
    HistoryEvent::EVENT_CUSTOMER_CHANGE_QUALITY->value => '_event_customer_change_quality',
    HistoryEvent::EVENT_INCOMING_CALL->value => '_event_call',
    HistoryEvent::EVENT_OUTGOING_CALL->value => '_event_call',
];

$templateName = $renderers[$model->getEventAsType()->value] ?? $renderers[HistoryEvent::EVENT_DEFAULT->value];

echo $this->render($templateName, [
    'model' => $model,
]);
