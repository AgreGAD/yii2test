<?php

namespace app\components\HistoryBodyFormatter\EventFormatter;

use app\components\HistoryBodyFormatter\HistoryFormatterInterface;

interface EventFormatterInterface extends HistoryFormatterInterface
{
    public function getSupportedEvents(): array;
}
