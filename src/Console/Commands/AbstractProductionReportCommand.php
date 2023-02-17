<?php

namespace Dainsys\RingCentral\Console\Commands;

use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Facades\Excel;
use Dainsys\RingCentral\Reports\ReportsContract;
use Dainsys\RingCentral\Mail\ProductionReportMail;
use Dainsys\RingCentral\Exports\ProductionReportExport;

abstract class AbstractProductionReportCommand extends Command
{
    protected array $dates;

    protected array $teams = ['ECC%'];

    abstract public function subject(): string;

    abstract public function dialGroupPrefixes(): array;

    abstract public function teams(): array;

    abstract public function dialGroups(): array;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function manage(\Dainsys\RingCentral\Reports\AbstractProductionReport $report)
    {
        $this->dates = $this->parseDates($this->option('dates'));
        $file_name = $this->fileName();

        $report->handle($this->dates, [
            'dial_group_prefix' => $this->dialGroupPrefixes(),
            'team' => $this->teams(),
            'dial_group' => $this->dialGroups(),
            'agent_name' => '%'
        ]);

        $report_name = get_class($this);

        if ($report->hasNewData() && $this->createFile($file_name, $report)) {
            Mail::send(
                new ProductionReportMail($this->subject(), $file_name, $report_name)
            );

            $this->info("{$this->subject()} sent!");
        }

        return 0;
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

    protected function createFile(string $file_name, ReportsContract $reportsContract): bool
    {
        if ($reportsContract instanceof \Dainsys\RingCentral\Reports\ProductionReportByDates) {
            return Excel::store(new ProductionReportExport($reportsContract->hours(), $reportsContract->calls()), $file_name);
        }

        throw new \Exception('Export Implementation Not Found!', 404);
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
