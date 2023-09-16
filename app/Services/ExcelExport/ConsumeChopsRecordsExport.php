<?php

namespace App\Services\ExcelExport;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class ConsumeChopsRecordsExport implements FromArray, WithStrictNullComparison
{
    protected $consumeChopsRecords;

    public function __construct(array $consumeChopsRecords)
    {
        $this->consumeChopsRecords = $consumeChopsRecords;
    }

    public function title(): array
    {
        return [
            [
                'name',
                'phone',
                'branch name',
                'type',
                'time',
                'amount',
                'remark'
            ]
        ];
    }

    private function mappingData(array $consumeChopsRecords): array
    {
        return collect($consumeChopsRecords)->map(function ($consumeChopsRecord) {
            return [
                $consumeChopsRecord->member->getFullNameAttribute(),
                $consumeChopsRecord->member->phone,
                $consumeChopsRecord->branch->getStoreNameWithId(),
                $consumeChopsRecord->type,
                $consumeChopsRecord->created_at,
                $consumeChopsRecord->consume_chops,
                $consumeChopsRecord->remark
            ];
        })->toArray();
    }

    public function array(): array
    {
        return array_merge(
            $this->title(),
            $this->mappingData($this->consumeChopsRecords)
        );
    }
}
