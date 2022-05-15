<?php

use app\models\search\HistorySearch;
use app\components\HistoryBodyFormatter\HistoryBodyFormatter;
use yii\helpers\Html;

/** @var $model HistorySearch */
/** @var HistoryBodyFormatter $formatter */
$formatter = Yii::$container->get(HistoryBodyFormatter::class);

$fax = $model->fax;

echo $this->render('_item_common', [
    'user' => $model->user,
    'body' => $formatter->format($model) .
        ' - ' .
        (isset($fax->document) ? Html::a(
            Yii::t('app', 'view document'),
            $fax->document->getViewUrl(),
            [
                'target' => '_blank',
                'data-pjax' => 0
            ]
        ) : ''),
    'footer' => Yii::t('app', '{type} was sent to {group}', [
        'type' => $fax ? $fax->getTypeText() : 'Fax',
        'group' => isset($fax->creditorGroup) ? Html::a($fax->creditorGroup->name, ['creditors/groups'], ['data-pjax' => 0]) : ''
    ]),
    'footerDatetime' => $model->ins_ts,
    'iconClass' => 'fa-fax bg-green'
]);
