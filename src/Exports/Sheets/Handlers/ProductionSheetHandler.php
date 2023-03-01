<?php

namespace Dainsys\RingCentral\Exports\Sheets\Handlers;

use Dainsys\RingCentral\Formats\TextFormats;

class ProductionSheetHandler extends AbstractSheetHandler
{
    /**
     * @return string
     */
    protected function lastColumn(): string
    {
        return 'S';
    }

    /**
     * @return string
     */
    protected function firstValuesColumn(): string
    {
        return 'E';
    }

    /**
     * @return string
     */
    protected function freezePaneOn(): string
    {
        return 'E3';
    }

    protected function addSubtotalFormulas(): void
    {
        if ($this->totalsRow > 0) {
            // Subtotal
            foreach (range('E', 'J') as $column) {
                $this->sheet->setCellValue("{$column}{$this->totalsRow}", "=SUBTOTAL(9,{$column}3:{$column}{$this->lastDataRow})");
            }

            // SPH
            $this->sheet->setCellValue("K{$this->totalsRow}", "=IFERROR(J{$this->totalsRow}/F{$this->totalsRow}, 0)");
            // Conversion
            $this->sheet->setCellValue("L{$this->totalsRow}", "=IFERROR(J{$this->totalsRow}/I{$this->totalsRow}, 0)");
            // Efficiency
            $this->sheet->setCellValue("M{$this->totalsRow}", "=IFERROR(F{$this->totalsRow}/E{$this->totalsRow}, 0)");
            // Occupancy
            $this->sheet->setCellValue("N{$this->totalsRow}", "=SUMPRODUCT(SUBTOTAL(109,OFFSET(E3,ROW(E3:E{$this->lastDataRow})-ROW(E3),)),N3:N{$this->lastDataRow}) / E{$this->totalsRow}");

            // Sumproduct
            // SUMPRODUCT(SUBTOTAL(109,OFFSET(E3,ROW(E3:E15)-ROW(E3),)),H3:H15)
            $callsColumn = 'H';
            foreach (range('O', 'S') as $column) {
                $this->sheet->setCellValue("{$column}{$this->totalsRow}", "=SUMPRODUCT(SUBTOTAL(109,OFFSET({$callsColumn}3,ROW({$callsColumn}3:{$callsColumn}{$this->lastDataRow})-ROW({$callsColumn}3),)),{$column}3:{$column}{$this->lastDataRow}) / {$callsColumn}{$this->totalsRow}");
                // SUMPRODUCT(SUBTOTAL(109,OFFSET(H3,ROW(H3:H357)-ROW(H3),)),O3:O357) / H358
            }
        }
    }

    /**
     * @return array
     */
    protected function numberFormatsColumns(): array
    {
        return [
            TextFormats::FORMAT_ACCOUNTING_ENTIRE => ['H', 'I', 'J'], // Comma separated
            TextFormats::FORMAT_ACCOUNTING => ['E', 'F', 'G', 'K', 'O', 'P', 'Q', 'R', 'S'],
            TextFormats::FORMAT_PERCENTAGE => ['L', 'M', 'N'],
        ];
    }

    /**
     * With to be applied to columns. Return multipe arrays.
     *
     * @return array
     */
    public function columnsWith(): array
    {
        return [
            [
                'columns' => ['A', 'B'],
                'width' => 82,
                'units' => 'px',
            ],
            [
                'columns' => ['C', 'D'],
                'width' => 220,
                'units' => 'px',
            ]
        ];
    }
}
