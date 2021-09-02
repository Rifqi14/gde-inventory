<?php

namespace App\Console\Commands;

use App\Http\Controllers\Machine\IclockTranscationController;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

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
        $request        = new Request();
        // $request->start_date    = Carbon::createFromFormat('Y-m-d', '2021-08-01');
        // $request->finish_date   = Carbon::createFromFormat('Y-m-d', '2021-08-31');
        $transaction->index($request);
    }
}
