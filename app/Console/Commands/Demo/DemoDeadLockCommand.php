<?php

namespace App\Console\Commands\Demo;

use App\Jobs\Demo\DeadLock\DemoDeadLockJob;
use Illuminate\Console\Command;

class DemoDeadLockCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:demo-dead-lock';

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
        for ($i = 0; $i < 50; $i++) {
            DemoDeadLockJob::dispatch();
        }
    }
}
