<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class RunJobs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'Run:Jobs';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Job in queue';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Artisan::call('queue:restart');
        Artisan::call('queue:work');
    }
}
