<?php

namespace app\components\HistoryBodyFormatter\EventFormatter;

use app\models\Customer;
use app\models\History;
use app\models\types\HistoryEvent;

class HistoryCustomerChangeTypeEventFormatter implements EventFormatterInterface
{
    public function getSupportedEvents(): array
    {
        return [
            HistoryEvent::EVENT_CUSTOMER_CHANGE_TYPE,
        ];
    }

    public function format(History $history): string
    {
        return "$history->eventText ".
            (Customer::getTypeTextByType($history->getDetailOldValue('type')) ?? 'not set').' to '.
            (Customer::getTypeTextByType($history->getDetailNewValue('type')) ?? 'not set');
    }
}
