<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DatabaseCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:base-artisan';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Data base command';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('key:generate');
        Artisan::call('jwt:secret');
        Artisan::call('l5-swagger:generate');
        Artisan::call('telescope:publish');
        Artisan::call('migrate:fresh');
        Artisan::call('db:seed DataUserSeeder');
        Artisan::call('db:seed DataGiftSeeder');
    }
}
