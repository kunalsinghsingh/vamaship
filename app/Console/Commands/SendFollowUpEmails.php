<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Repositories\Eloquent\CronRepo as CronRepo;
class SendFollowUpEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:sendFollowUpEmails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'To send follow ups emails';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ccRepo                 = new CronRepo;
        $ccRepo->followupEmail();
        echo "we arrived";
    }
}
