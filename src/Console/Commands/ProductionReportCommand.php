<?php

namespace Dainsys\RingCentral\Console\Commands;

use Illuminate\Support\Str;
use Dainsys\Mailing\Mailing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Dainsys\RingCentral\Mail\ProductionReportMail;
use Dainsys\RingCentral\Exports\ProductionReportExport;

abstract class ProductionReportCommand extends Command
{
    protected array $dates;
    protected array $dialGroups;
    protected array $teams;
    protected string $file_name;

    abstract public function subject(): string;

    abstract public function dialGroups(): array;

    abstract public function teams(): array;

    abstract public function sheets(): array;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->dates = $this->parseDates($this->argument('dates'));
        $this->file_name = $this->fileName();

        $report = new ProductionReportExport(
            $this->sheets(),
            $this->dialGroups(),
            $this->teams(),
            $this->dates
        );

        if (Excel::store($report, $this->file_name)) {
            if ($report->hasNewData()) {
                Mail::to(Mailing::recipients(get_class($this)))
                ->send(
                    new ProductionReportMail($this->subject(), $this->file_name)
                );

                $this->info("{$this->subject()} sent!");
            } else {
                $this->warn('Data already sent. Clear cache to re-send it!');

                Storage::delete($this->file_name);
            }

            return 0;
        }

        return 1;
    }

    protected function parseDates($dates): array
    {
        $arrayDates = strToArray(
            $dates ?: now()->format('Y-m-d'),
            2
        );

        if (count($arrayDates) === 1) {
            $arrayDates[] = $arrayDates[0];
        }

        return $arrayDates;
    }

    protected function fileName(): string
    {
        return join('-', [
            Str::kebab($this->subject()),
            $this->dates[0],
            $this->dates[1] ?? $this->dates[0],
        ]) . '.XLSX';
    }
}
