<?php

use App\Jobs\InspireJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new InspireJob())
    ->everyMinute()
    ->onOneServer();
