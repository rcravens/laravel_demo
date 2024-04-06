<?php

use App\Jobs\InspireJob;
use Illuminate\Support\Facades\Schedule;

Schedule::job( new InspireJob() )
        ->everyMinute()
        ->onOneServer();

