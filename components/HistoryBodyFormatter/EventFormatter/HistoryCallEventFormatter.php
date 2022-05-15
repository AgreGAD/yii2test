<?php

namespace app\components\HistoryBodyFormatter\EventFormatter;

use app\models\History;
use app\models\types\HistoryEvent;

class HistoryCallEventFormatter implements EventFormatterInterface
{
    public function getSupportedEvents(): array
    {
        return [
            HistoryEvent::EVENT_INCOMING_CALL,
            HistoryEvent::EVENT_OUTGOING_CALL,
        ];
    }

    public function format(History $history): string
    {
        $call = $history->call;

        return $call
            ? $call->totalStatusText.(
                $call->getTotalDisposition(false)
                ? " <span class='text-grey'>".$call->getTotalDisposition(false).'</span>'
                : ''
            )
            : '<i>Deleted</i> ';
    }
}
