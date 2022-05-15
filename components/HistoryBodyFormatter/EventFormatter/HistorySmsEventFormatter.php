<?php

namespace app\components\HistoryBodyFormatter\EventFormatter;

use app\models\History;
use app\models\types\HistoryEvent;

class HistorySmsEventFormatter implements EventFormatterInterface
{
    public function getSupportedEvents(): array
    {
        return [
            HistoryEvent::EVENT_INCOMING_SMS,
            HistoryEvent::EVENT_OUTGOING_SMS,
        ];
    }

    public function format(History $history): string
    {
        return $history->sms->message ? $history->sms->message : '';
    }
}
