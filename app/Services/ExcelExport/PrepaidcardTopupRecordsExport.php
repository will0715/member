<?php

namespace App\Services\ExcelExport;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class PrepaidcardTopupRecordsExport implements FromArray, WithStrictNullComparison
{
    protected $prepaidcardTopupRecords;

    public function __construct(array $prepaidcardTopupRecords)
    {
        $this->prepaidcardTopupRecords = $prepaidcardTopupRecords;
    }

    public function title(): array
    {
        return [
            [
                'name',
                'phone',
                'branch name',
                'time',
                'amount'
            ]
        ];
    }

    private function mappingData(array $prepaidcardTopupRecords): array
    {
        return collect($prepaidcardTopupRecords)->map(function ($prepaidcardTopupRecord) {
            return [
                $prepaidcardTopupRecord->member->getFullNameAttribute(),
                $prepaidcardTopupRecord->member->phone,
                $prepaidcardTopupRecord->branch->getStoreNameWithId(),
                $prepaidcardTopupRecord->created_at,
                $prepaidcardTopupRecord->topup
            ];
        })->toArray();
    }

    public function array(): array
    {
        return array_merge(
            $this->title(),
            $this->mappingData($this->prepaidcardTopupRecords)
        );
    }
}
