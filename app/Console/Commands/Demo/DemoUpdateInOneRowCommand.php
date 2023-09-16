<?php

namespace App\Console\Commands\Demo;

use App\Jobs\Demo\UpdateInOneRow\UsingAtomicLockInOneRowJob;
use App\Jobs\Demo\UpdateInOneRow\UsingLockForUpdateInOneRowJob;
use App\Jobs\Demo\UpdateInOneRow\UsingNormalUpdateInOneRowJob;
use App\Jobs\Demo\UpdateInOneRow\UsingUpdateOnQueryInOneRowJob;
use Illuminate\Console\Command;

class DemoUpdateInOneRowCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:demo-update-in-one-row {type?}';

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
        $type = $this->argument('type') ?: 0;

        switch ($type) {
            case 1:
                $this->usingAtomicLockLaravel();
                break;
            case 2:
                $this->usingLockForUpdateMysql();
                break;
            case 3:
                $this->usingUpdateOnQueryMySql();
                break;
            default:
                $this->usingNormal();
                break;
        }
    }

    public function usingNormal()
    {
        for ($i = 0; $i < 100; $i++) {
            UsingNormalUpdateInOneRowJob::dispatch();
        }
    }

    public function usingAtomicLockLaravel()
    {
        for ($i = 0; $i < 100; $i++) {
            UsingAtomicLockInOneRowJob::dispatch();
        }
    }

    public function usingLockForUpdateMysql()
    {
        for ($i = 0; $i < 100; $i++) {
            UsingLockForUpdateInOneRowJob::dispatch();
        }
    }

    public function usingUpdateOnQueryMySql()
    {
        for ($i = 0; $i < 100; $i++) {
            UsingUpdateOnQueryInOneRowJob::dispatch();
        }
    }
}
