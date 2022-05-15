<?php

namespace app\components\HistoryBodyFormatter\EventFormatter;

use app\models\History;
use app\models\types\HistoryEvent;

class HistoryTaskEventFormatter implements EventFormatterInterface
{
    public function getSupportedEvents(): array
    {
        return [
            HistoryEvent::EVENT_CREATED_TASK,
            HistoryEvent::EVENT_COMPLETED_TASK,
            HistoryEvent::EVENT_UPDATED_TASK,
        ];
    }

    public function format(History $history): string
    {
        $task = $history->task;

        return "$history->eventText: ".($task->title ?? '');
    }
}
