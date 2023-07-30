<?php

namespace App\Services\ExcelExport;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Illuminate\Database\Eloquent\Collection;

class MemberRegisterBranchStatisticsRecordsExport implements FromArray, WithStrictNullComparison
{
    protected $memberRegisterBranchStatisticsRecords;

    public function __construct(Collection $memberRegisterBranchStatisticsRecords)
    {
        $this->memberRegisterBranchStatisticsRecords = $memberRegisterBranchStatisticsRecords;
    }

    public function title(): array
    {
        return [
            [
                'name',
                'code',
                'store_name',
                'register members count',
            ]
        ];
    }

    private function mappingData(Collection $memberRegisterBranchStatisticsRecords): array
    {
        return collect($memberRegisterBranchStatisticsRecords)->map(function ($memberRegisterBranchStatisticsRecord) {
            return [
                $memberRegisterBranchStatisticsRecord->name,
                $memberRegisterBranchStatisticsRecord->code,
                $memberRegisterBranchStatisticsRecord->store_name,
                $memberRegisterBranchStatisticsRecord->register_members_count,
            ];
        })->toArray();
    }

    public function array(): array
    {
        return array_merge(
            $this->title(),
            $this->mappingData($this->memberRegisterBranchStatisticsRecords)
        );
    }
}
