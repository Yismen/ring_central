<?php

namespace Dainsys\RingCentral\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductionReportExport implements ExportContract, WithMultipleSheets
{
    public array $work_sheets;
    public array $fields;
    public array $dial_groups;
    public array $teams;
    public array $dates;

    public function __construct(array $work_sheets, array $dial_groups, array $teams, array $dates)
    {
        $this->work_sheets = $work_sheets;
        $this->dial_groups = $dial_groups;
        $this->teams = $teams;
        $this->dates = $dates;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->work_sheets as $sheet) {
            $sheets[] = new $sheet($this->dial_groups, $this->teams, $this->dates);
        }
        $this->work_sheets = $sheets;
        return $sheets;
    }

    /**
     * @return bool
     */
    public function hasNewData(): bool
    {
        foreach ($this->work_sheets as $sheet) {
            if ($sheet->hasNewData()) {
                return true;
            }
        }

        return false;
    }
}
