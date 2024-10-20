<?php

use App\Jobs\InspireJob;
use App\Jobs\TestJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job( new InspireJob() )
        ->everyMinute()
        ->onOneServer();

Schedule::job(new TestJob() )
        ->everyMinute()
        ->onOneServer();

