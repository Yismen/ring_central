<?php

namespace Dainsys\RingCentral\Exports\Sheets\Handlers;

use Maatwebsite\Excel\Sheet;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Illuminate\Database\Eloquent\Collection;

abstract class AbstractSheetHandler
{
    protected Sheet $sheet;
    protected Collection $data;
    protected int $rows;
    protected int $lastDataRow;
    protected int $totalsRow;

    // abstract protected function autofitRange(): string;

    public function __invoke(AfterSheet $sheet)
    {
        $this->sheet = $sheet->getSheet();
        $this->data = $sheet->getConcernable()->data;
        $this->rows = $this->data->count();
        $this->lastDataRow = $this->rows + 2;
        $this->totalsRow = $this->lastDataRow + 1;

        foreach ($this->steps() as $function) {
            $this->$function();
        }
    }

    /**
     * With to be applied to columns. Return multipe arrays.
     *
     * @return array
     */
    abstract protected function columnsWith(): array;

    abstract protected function lastColumn(): string;

    abstract protected function firstValuesColumn(): string;

    abstract protected function freezePaneOn(): string;

    abstract protected function numberFormatsColumns(): array;

    abstract protected function addSubtotalFormulas(): void;

    public function steps(): array
    {
        return [
            'formatTitleRow',
            'applyFilters',
            'formatTableBody',
            'formatColumns',
            'formatHeaders',
            'formatTotals',
            'freezePane',
            'setColumnsWidth',
            'addSubtotalFormulas',
        ];
    }

    protected function formatTitleRow()
    {
        $this->sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 16
            ],
        ]);
    }

    protected function applyFilters()
    {
        $this->sheet->setAutoFilter("A2:{$this->lastColumn()}{$this->lastDataRow}");
    }

    protected function formatTableBody()
    {
        $this->sheet->getStyle("A3:{$this->lastColumn()}{$this->lastDataRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => 'left',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK
                ],
                'inside' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
            ],
        ]);

        $this->sheet->getStyle("{$this->firstValuesColumn()}3:{$this->lastColumn()}{$this->totalsRow}")->applyFromArray([
            'alignment' => [
                'horizontal' => 'right',
            ],
        ]);
    }

    protected function formatHeaders()
    {
        $this->sheet->getStyle("A2:{$this->lastColumn()}2")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => 'center',
                'vertical' => 'top',
                'wrapText' => true
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK
                ],
                'inside' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => [
                    'rgb' => 'D9D9D9',
                ],
            ],
        ]);
    }

    protected function formatTotals()
    {
        $this->sheet->getStyle("A{$this->totalsRow}:{$this->lastColumn()}{$this->totalsRow}")->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 12
            ],
            'alignment' => [
                'horizontal' => 'right',
                'vertical' => 'top',
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THICK
                ],
                'inside' => [
                    'borderStyle' => Border::BORDER_THIN
                ],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'color' => [
                    'rgb' => 'F2F2F2',
                ],
            ],
        ]);
    }

    protected function formatColumns()
    {
        foreach ($this->numberFormatsColumns() as $format => $columns) {
            foreach ($columns as $column) {
                $this->sheet->getStyle("{$column}3:{$column}{$this->totalsRow}")->getNumberFormat()->setFormatCode($format);
            }
        }
    }

    protected function freezePane()
    {
        $this->sheet->freezePane($this->freezePaneOn());
    }

    protected function setColumnsWidth()
    {
        $this->sheet->getDefaultColumnDimension()->setWidth(60, 'px');
        foreach ($this->columnsWith() as $set) {
            $width = $set['width'];
            $units = $set['units'] ?? 'px';
            foreach ($set['columns'] as $column) {
                $this->sheet->getColumnDimension($column)->setWidth($width, $units);
            }
            // code...
        }
    }
}
