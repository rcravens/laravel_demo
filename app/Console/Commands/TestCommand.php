<?php

namespace App\Console\Commands;

use App\Jobs\TestJob;
use Illuminate\Console\Command;

class TestCommand extends Command
{
    protected $signature = 'app:test-command';

    protected $description = 'Starts a test job.';

    public function handle()
    {
        $job = new TestJob();
        dispatch( $job );
    }
}
