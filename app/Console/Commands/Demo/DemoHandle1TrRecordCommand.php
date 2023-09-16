<?php

namespace App\Console\Commands\Demo;

use App\Jobs\Demo\Handle1TrRow\Handle1TrRowJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DemoHandle1TrRecordCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:demo-handle-1tr-record';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Handle1TrRowJob::dispatch();
    }
}
