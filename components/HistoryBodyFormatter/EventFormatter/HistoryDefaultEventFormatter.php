<?php

namespace app\components\HistoryBodyFormatter\EventFormatter;

use app\models\History;
use app\models\types\HistoryEvent;

class HistoryDefaultEventFormatter implements EventFormatterInterface
{
    public function getSupportedEvents(): array
    {
        return [
            HistoryEvent::EVENT_DEFAULT,
            HistoryEvent::EVENT_OUTGOING_FAX,
            HistoryEvent::EVENT_INCOMING_FAX,
        ];
    }

    public function format(History $history): string
    {
        return $history->eventText;
    }
}
