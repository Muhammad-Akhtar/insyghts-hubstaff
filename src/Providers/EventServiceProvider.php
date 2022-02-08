<?php

namespace Insyghts\Hubstaff\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Insyghts\Hubstaff\Events\AttendanceLogCreated;
use Insyghts\Hubstaff\Listeners\AttendanceLogListener;
use Insyghts\Hubstaff\Events\AttendanceLogSavingEvent;
use Insyghts\Hubstaff\Listeners\AttendanceLogSavingListener;

class EventServiceProvider extends ServiceProvider
{
    // this is just an example event
    protected $listen = [
        // AttendanceLogSavingEvent::class => [
        //     AttendanceLogSavingListener::class
        // ]
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
    }
}