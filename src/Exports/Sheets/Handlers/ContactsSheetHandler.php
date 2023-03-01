<?php

namespace Dainsys\RingCentral\Exports\Sheets\Handlers;

use Dainsys\RingCentral\Formats\TextFormats;

class ContactsSheetHandler extends AbstractSheetHandler
{
    /**
     * @return string
     */
    protected function lastColumn(): string
    {
        return 'M';
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
            foreach (range('E', 'K') as $column) {
                $this->sheet->setCellValue("{$column}{$this->totalsRow}", "=SUBTOTAL(9,{$column}3:{$column}{$this->lastDataRow})");
            }
        }
    }

    /**
     * @return array
     */
    protected function numberFormatsColumns(): array
    {
        return [
            TextFormats::FORMAT_ACCOUNTING_ENTIRE => range('E', 'K'), // Comma separated
            // TextFormats::FORMAT_ACCOUNTING => ['E', 'F', 'G', 'K', 'O', 'P', 'Q', 'R', 'S'],
            // TextFormats::FORMAT_PERCENTAGE => ['L', 'M', 'N'],
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
                'columns' => ['A', 'L'],
                'width' => 82,
                'units' => 'px',
            ],
            [
                'columns' => ['B', 'C', 'D', 'M'],
                'width' => 220,
                'units' => 'px',
            ]
        ];
    }
}
