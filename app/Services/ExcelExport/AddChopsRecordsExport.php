<?php

namespace App\Services\ExcelExport;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class AddChopsRecordsExport implements FromArray, WithStrictNullComparison
{
    protected $addChopsRecords;

    public function __construct(array $addChopsRecords)
    {
        $this->addChopsRecords = $addChopsRecords;
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

    private function mappingData(array $addChopsRecords): array
    {
        return collect($addChopsRecords)->map(function ($addChopsRecord) {
            return [
                $addChopsRecord->member->getFullNameAttribute(),
                $addChopsRecord->member->phone,
                $addChopsRecord->branch->getStoreNameWithId(),
                $addChopsRecord->type,
                $addChopsRecord->created_at,
                $addChopsRecord->chops,
                $addChopsRecord->remark
            ];
        })->toArray();
    }

    public function array(): array
    {
        return array_merge(
            $this->title(),
            $this->mappingData($this->addChopsRecords)
        );
    }
}
