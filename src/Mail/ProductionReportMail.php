<?php

namespace Dainsys\RingCentral\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProductionReportMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public string $title;
    protected array $files;

    public function __construct(string $title, ...$files)
    {
        $this->title = $title;
        $this->files = $files;
    }

    public function build()
    {
        $this
            ->subject($this->title)
            ->markdown('ring_central::mail.production_report');

        foreach ($this->files as $file) {
            $this->attachFromStorage($file);
        }

        return $this;
    }
}
