<?php

namespace App\Services\ExcelExport;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class PrepaidcardPaymentRecordsExport implements FromArray, WithStrictNullComparison
{
    protected $prepaidcardPaymentRecords;

    public function __construct(array $prepaidcardPaymentRecords)
    {
        $this->prepaidcardPaymentRecords = $prepaidcardPaymentRecords;
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

    private function mappingData(array $prepaidcardPaymentRecords): array
    {
        return collect($prepaidcardPaymentRecords)->map(function ($prepaidcardPaymentRecord) {
            return [
                $prepaidcardPaymentRecord->member->getFullNameAttribute(),
                $prepaidcardPaymentRecord->member->phone,
                $prepaidcardPaymentRecord->branch->getStoreNameWithId(),
                $prepaidcardPaymentRecord->created_at,
                $prepaidcardPaymentRecord->payment
            ];
        })->toArray();
    }

    public function array(): array
    {
        return array_merge(
            $this->title(),
            $this->mappingData($this->prepaidcardPaymentRecords)
        );
    }
}
