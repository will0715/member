<?php

namespace App\Helpers;

use Carbon\Carbon;

class ReportHelper {
    static public function groupByCreatedDate()
    {
        return function($item) {
            return Carbon::parse($item->created_at)->format('Y-m-d');
        };
    }

    static public function sumByColumn($column)
    {
        return function($row) use ($column) {
            return $row->sum($column);
        };
    }
}