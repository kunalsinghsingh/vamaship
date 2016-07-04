<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel {

    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\Inspire::class,
         \App\Console\Commands\SendFollowUpEmails::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule) {
        $schedule->command('inspire')->hourly();
        $schedule->command('sendFollowUpEmails')->dailyAt('9:00');
        $schedule->call(function () {
            // Runs once a week on Monday at 13:00...
            $content = array();
            Mail::send('Email.EmailTemplate', $content, function($message) use ($content) {
                $message->to('archana@infiniteit.biz', 'Approach')->from('crm@approach.com')->subject('Email');
            });
        })->weekly()->tuesdays()->at('17:01');
    }

}
