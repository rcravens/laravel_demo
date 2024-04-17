<?php

namespace App\Console\Commands;

use App\Demo\Utilities\IntegrityChecker;
use Illuminate\Console\Command;

class SignCode extends Command
{
    protected $signature = 'app:sign-code';

    protected $description = 'Signs the code using the integrity checker.';

    public function handle()
    {
        $integrity_checker = new IntegrityChecker( base_path() );
        $integrity_checker->sign();
        $integrity_checker->verify();
    }
}
