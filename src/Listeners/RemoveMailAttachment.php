<?php

namespace Dainsys\RingCentral\Listeners;

use Illuminate\Mail\Events\MessageSent;
use Illuminate\Support\Facades\Storage;

class RemoveMailAttachment
{
    /**
         * Create the event listener.
         *
         * @return void
         */
    public function __construct()
    {
    }

    public function handle(MessageSent $event)
    {
        foreach ($event->message->getChildren() as $file) {
            if ($file instanceof \Swift_Attachment) {
                if (Storage::exists($file->getFilename())) {
                    Storage::delete($file->getFilename());
                }
            }
        }
    }
}
