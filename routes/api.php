<?php

use Illuminate\Support\Facades\Route;
use Dainsys\RingCentral\Models\Form;
use Dainsys\RingCentral\Models\Entry;
use Dainsys\RingCentral\Models\Response;
use Dainsys\RingCentral\Http\Resources\FormResource;
use Dainsys\RingCentral\Http\Resources\EntryResource;

Route::middleware(['api'])->group(function () {
    // Auth Routes
    Route::as('dainsys.ring_central.api.')
        ->prefix('dainsys/ring_central/api')
        ->middleware(
            preg_split('/[,|]+/', config('ring_central.midlewares.api'), -1, PREG_SPLIT_NO_EMPTY)
        )->group(function () {
            // Route::get('form/{form}', function ($form) {
            //     return new FormResource(Form::with('responses')->find($form));
            // })->name('form.show');
            // Route::get('entries/{entry}', function ($entry) {
            //     return new EntryResource(Entry::with('form', 'responses')->findOrFail($entry));
            // })->name('entries.show');
            // Route::get('responses/entry/{entry}', ['data' => 'response by entry'])->name('responses.entry.show');
        });
});
