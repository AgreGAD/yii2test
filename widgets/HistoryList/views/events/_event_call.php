<?php

use app\models\search\HistorySearch;
use app\components\HistoryBodyFormatter\HistoryBodyFormatter;
use app\models\Call;

/** @var $model HistorySearch */
/** @var HistoryBodyFormatter $formatter */
$formatter = Yii::$container->get(HistoryBodyFormatter::class);

/** @var Call $call */
$call = $model->call;
$answered = $call && $call->status == Call::STATUS_ANSWERED;

echo $this->render('_item_common', [
    'user' => $model->user,
    'content' => $call->comment ?? '',
    'body' => $formatter->format($model),
    'footerDatetime' => $model->ins_ts,
    'footer' => isset($call->applicant) ? "Called <span>{$call->applicant->name}</span>" : null,
    'iconClass' => $answered ? 'md-phone bg-green' : 'md-phone-missed bg-red',
    'iconIncome' => $answered && $call->direction == Call::DIRECTION_INCOMING
]);
