<?php

namespace app\components\HistoryBodyFormatter;

use app\components\HistoryBodyFormatter\EventFormatter\EventFormatterInterface;
use app\models\History;
use app\models\types\HistoryEvent;

class HistoryBodyFormatter
{
    private array $formatters = [];

    public function __construct(array $formatters)
    {
        foreach ($formatters as $formatter) {
            $this->registerFormatter($formatter);
        }
    }

    private function registerFormatter(EventFormatterInterface $formatter): void
    {
        foreach ($formatter->getSupportedEvents() as $event) {
            $this->formatters[$event->value] = $formatter;
        }
    }

    public function format(History $historyRecord): string
    {
        return $this->getFormatter($historyRecord->getEventAsType())->format($historyRecord);
    }

    private function getFormatter(HistoryEvent $event): HistoryFormatterInterface
    {
        return $this->formatters[$event->value] ?? $this->formatters[HistoryEvent::EVENT_DEFAULT->value];
    }
}
