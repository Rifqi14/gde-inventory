<?php

namespace App\Console\Commands;

use App\Http\Controllers\Machine\IclockTranscationController;
use Illuminate\Console\Command;

class SyncAttendanceCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:sync';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to get attendance in syncronhize';

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
        \Log::info("Cron is working fine!");
        $transaction    = new IclockTranscationController();
        $transaction->index();
    }
}
