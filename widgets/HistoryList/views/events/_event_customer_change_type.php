<?php

use app\models\search\HistorySearch;
use app\models\Customer;

/** @var $model HistorySearch */

echo $this->render('_item_statuses_change', [
    'model' => $model,
    'oldValue' => Customer::getTypeTextByType($model->getDetailOldValue('type')),
    'newValue' => Customer::getTypeTextByType($model->getDetailNewValue('type'))
]);
