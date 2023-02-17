<?php

namespace Dainsys\RingCentral\Mail;

use Dainsys\Mailing\Mailing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductionReportMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public string $title;

    protected $files;

    protected string $report_name;

    /**
     * Summary of __construct
     * @param string $title
     * @param mixed  $files
     * @param string $report_name
     */
    public function __construct(string $title, $files, string $report_name)
    {
        $this->title = $title;
        $this->files = $files;
        $this->report_name = $report_name;
    }

    public function build()
    {
        $this
            ->subject($this->title)
            ->to(Mailing::recipients($this->report_name))
            ->markdown('ring_central::mail.production_report');

        if (is_array($this->files)) {
            foreach ($this->files as $file) {
                $this->attachFromStorage($file);
            }
        } else {
            $this->attachFromStorage($this->files);
        }

        return $this;
    }
}
