<?php

use app\models\search\HistorySearch;
use app\models\Customer;

/** @var $model HistorySearch */

echo $this->render('_item_statuses_change', [
    'model' => $model,
    'oldValue' => Customer::getQualityTextByQuality($model->getDetailOldValue('quality')),
    'newValue' => Customer::getQualityTextByQuality($model->getDetailNewValue('quality')),
]);
