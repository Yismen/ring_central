<?php

namespace Dainsys\RingCentral\Formats;

use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TextFormats extends NumberFormat
{
    public const FORMAT_ACCOUNTING = '_(* #,##0.00_);_(* (#,##0.00);_(* "-"??_);_(@_)';
    public const FORMAT_ACCOUNTING_ENTIRE = '_(* #,##0_);_(* (#,##0);_(* "-"_);_(@_)';
}
