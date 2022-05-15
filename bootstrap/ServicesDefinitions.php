<?php

namespace app\bootstrap;

use app\components\HistoryBodyFormatter\EventFormatter\HistoryCallEventFormatter;
use app\components\HistoryBodyFormatter\EventFormatter\HistoryCustomerChangeQualityEventFormatter;
use app\components\HistoryBodyFormatter\EventFormatter\HistoryCustomerChangeTypeEventFormatter;
use app\components\HistoryBodyFormatter\EventFormatter\HistoryDefaultEventFormatter;
use app\components\HistoryBodyFormatter\EventFormatter\HistorySmsEventFormatter;
use app\components\HistoryBodyFormatter\EventFormatter\HistoryTaskEventFormatter;
use app\components\HistoryBodyFormatter\HistoryBodyFormatter;
use yii\base\BootstrapInterface;

class ServicesDefinitions implements BootstrapInterface
{
    public function bootstrap($app)
    {
        $container = \Yii::$container;

        $container->setSingleton(HistoryBodyFormatter::class, new HistoryBodyFormatter([
            new HistoryDefaultEventFormatter(),
            new HistoryTaskEventFormatter(),
            new HistorySmsEventFormatter(),
            new HistoryCustomerChangeTypeEventFormatter(),
            new HistoryCustomerChangeQualityEventFormatter(),
            new HistoryCallEventFormatter(),
        ]));
    }
}
