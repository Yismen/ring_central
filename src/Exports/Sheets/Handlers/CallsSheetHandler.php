<?php

namespace Dainsys\RingCentral\Exports\Sheets\Handlers;

use Dainsys\RingCentral\Formats\TextFormats;

class CallsSheetHandler extends AbstractSheetHandler
{
    /**
     * @return string
     */
    protected function lastColumn(): string
    {
        return 'K';
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
            foreach (range('E', 'G') as $column) {
                $this->sheet->setCellValue("{$column}{$this->totalsRow}", "=SUBTOTAL(9,{$column}3:{$column}{$this->lastDataRow})");
            }

            // Sumproduct
            // SUMPRODUCT(SUBTOTAL(109,OFFSET(E3,ROW(E3:E15)-ROW(E3),)),H3:H15)
            $callsColumn = 'E';
            foreach (range('H', 'K') as $column) {
                $this->sheet->setCellValue("{$column}{$this->totalsRow}", "=SUMPRODUCT(SUBTOTAL(109,OFFSET({$callsColumn}3,ROW({$callsColumn}3:{$callsColumn}{$this->lastDataRow})-ROW({$callsColumn}3),)),{$column}3:{$column}{$this->lastDataRow}) / {$callsColumn}{$this->totalsRow}");
            }
        }
    }

    /**
     * @return array
     */
    protected function numberFormatsColumns(): array
    {
        return [
            TextFormats::FORMAT_ACCOUNTING_ENTIRE => ['E', 'F', 'G'], // Comma separated
            TextFormats::FORMAT_ACCOUNTING => range('H', 'K'),
        ];
    }
}
