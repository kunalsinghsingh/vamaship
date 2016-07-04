<?php

namespace App\Console\Commands;

use App\Models\Inquiry;
use App\Models\User;
use DB;
use Illuminate\Console\Command;
use Repositories\Eloquent\CallCenterRepo as CallCenterRepo;
class AssignLead extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:assignLead';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will be used to assign the leads';

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
        $ccRepo                 = new CallCenterRepo;
        $ccRepo->assignLead();
        echo "we arrived";
    }
}
