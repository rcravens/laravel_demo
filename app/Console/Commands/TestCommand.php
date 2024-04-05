<?php

namespace App\Console\Commands;

use App\Jobs\TestJob;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts a test job.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $job = new TestJob();
        dispatch($job);
    }
}
