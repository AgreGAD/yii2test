<?php

namespace app\components\HistoryBodyFormatter\EventFormatter;

use app\models\Customer;
use app\models\History;
use app\models\types\HistoryEvent;

class HistoryCustomerChangeQualityEventFormatter implements EventFormatterInterface
{
    public function getSupportedEvents(): array
    {
        return [
            HistoryEvent::EVENT_CUSTOMER_CHANGE_QUALITY,
        ];
    }

    public function format(History $history): string
    {
        return "$history->eventText ".
            (Customer::getQualityTextByQuality($history->getDetailOldValue('quality')) ?? 'not set').' to '.
            (Customer::getQualityTextByQuality($history->getDetailNewValue('quality')) ?? 'not set');
    }
}
