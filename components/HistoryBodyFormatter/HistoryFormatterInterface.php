<?php

namespace app\components\HistoryBodyFormatter;

use app\models\History;

interface HistoryFormatterInterface
{
    public function format(History $history): string;
}
