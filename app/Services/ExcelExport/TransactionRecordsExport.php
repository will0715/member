<?php

namespace App\Services\ExcelExport;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Illuminate\Database\Eloquent\Collection;

class TransactionRecordsExport implements FromArray, WithStrictNullComparison
{
    protected $transactionRecords;

    public function __construct(Collection $transactionRecords)
    {
        $this->transactionRecords = $transactionRecords;
    }

    public function title(): array
    {
        return [
            [
                'name',
                'phone',
                'branch name',
                'payment type',
                'clerk',
                'items count',
                'amount',
                'time',
                'status',
                'remark'
            ]
        ];
    }

    private function mappingData(Collection $transactionRecords): array
    {
        return collect($transactionRecords)->map(function ($transactionRecord) {
            return [
                $transactionRecord->member->getFullNameAttribute(),
                $transactionRecord->member->phone,
                $transactionRecord->branch->getStoreNameWithId(),
                $transactionRecord->payment_type,
                $transactionRecord->clerk,
                $transactionRecord->items_count,
                $transactionRecord->amount,
                $transactionRecord->created_at,
                $transactionRecord->status,
                $transactionRecord->remark
            ];
        })->toArray();
    }

    public function array(): array
    {
        return array_merge(
            $this->title(),
            $this->mappingData($this->transactionRecords)
        );
    }
}
