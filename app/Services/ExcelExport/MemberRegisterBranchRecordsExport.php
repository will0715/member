<?php

namespace App\Services\ExcelExport;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;
use Illuminate\Database\Eloquent\Collection;

class MemberRegisterBranchRecordsExport implements FromArray, WithStrictNullComparison
{
    protected $memberRegisterBranchRecords;

    public function __construct(Collection $memberRegisterBranchRecords)
    {
        $this->memberRegisterBranchRecords = $memberRegisterBranchRecords;
    }

    public function title(): array
    {
        return [
            [
                'first name',
                'last name',
                'phone',
                'branch name',
                'time'
            ]
        ];
    }

    private function mappingData(Collection $memberRegisterBranchRecords): array
    {
        return collect($memberRegisterBranchRecords)->map(function ($memberRegisterBranchRecord) {
            return [
                $memberRegisterBranchRecord->first_name,
                $memberRegisterBranchRecord->last_name,
                $memberRegisterBranchRecord->phone,
                $memberRegisterBranchRecord->registerBranch->getStoreNameWithId(),
                $memberRegisterBranchRecord->created_at,
            ];
        })->toArray();
    }

    public function array(): array
    {
        return array_merge(
            $this->title(),
            $this->mappingData($this->memberRegisterBranchRecords)
        );
    }
}
