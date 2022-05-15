<?php

use app\models\search\HistorySearch;
use app\components\HistoryBodyFormatter\HistoryBodyFormatter;

/** @var $model HistorySearch */
/** @var HistoryBodyFormatter $formatter */
$formatter = Yii::$container->get(HistoryBodyFormatter::class);

echo $this->render('_item_common', [
    'user' => $model->user,
    'body' => $formatter->format($model),
    'bodyDatetime' => $model->ins_ts,
    'iconClass' => 'fa-gear bg-purple-light'
]);
