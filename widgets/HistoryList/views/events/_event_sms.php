<?php

use app\models\search\HistorySearch;
use app\components\HistoryBodyFormatter\HistoryBodyFormatter;
use app\models\Sms;

/** @var $model HistorySearch */
/** @var HistoryBodyFormatter $formatter */
$formatter = Yii::$container->get(HistoryBodyFormatter::class);

echo $this->render('_item_common', [
    'user' => $model->user,
    'body' => $formatter->format($model),
    'footer' => $model->sms->direction == Sms::DIRECTION_INCOMING ?
        Yii::t('app', 'Incoming message from {number}', [
            'number' => $model->sms->phone_from ?? ''
        ]) : Yii::t('app', 'Sent message to {number}', [
            'number' => $model->sms->phone_to ?? ''
        ]),
    'iconIncome' => $model->sms->direction == Sms::DIRECTION_INCOMING,
    'footerDatetime' => $model->ins_ts,
    'iconClass' => 'icon-sms bg-dark-blue'
]);
