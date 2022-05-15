<?php
use app\models\search\HistorySearch;
use app\components\HistoryBodyFormatter\HistoryBodyFormatter;

/** @var $model HistorySearch */
/** @var HistoryBodyFormatter $formatter */
$formatter = Yii::$container->get(HistoryBodyFormatter::class);

$task = $model->task;

echo $this->render('_item_common', [
    'user' => $model->user,
    'body' => $formatter->format($model),
    'iconClass' => 'fa-check-square bg-yellow',
    'footerDatetime' => $model->ins_ts,
    'footer' => isset($task->customerCreditor->name) ? "Creditor: " . $task->customerCreditor->name : ''
]);
