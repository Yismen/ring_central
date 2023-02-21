<?php

namespace Dainsys\RingCentral\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class ProductionReportExport implements ExportContract, WithMultipleSheets
{
    public array $work_sheets;
    public array $fields;
    public array $dates;

    public function __construct(array $work_sheets, array $fields, array $dates)
    {
        $this->work_sheets = $work_sheets;
        $this->fields = $fields;
        $this->dates = $dates;
    }

    public function sheets(): array
    {
        $sheets = [];

        foreach ($this->work_sheets as $sheet) {
            $sheets[] = new $sheet($this->fields, $this->dates);
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
