<?php

use App\Demo\Utilities\IntegrityChecker;
use App\Http\Controllers\ProfileController;
use App\Jobs\TestJob;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get( '/', function () {

    Log::info( 'Running integrity checker' );

    // Integrity checker
    //
    $integrity_checker = new IntegrityChecker( realpath( app_path() . '/..' ) );

    $integrity_checker->verify( false );

    $is_integrity_okay = $integrity_checker->isOkay();

    // Cache (redis) load testing
    //
    $cache_key      = 'testing_1234567890';
    $num_cache_hits = request()->get( 'cache', 0 );
    for ( $i = 0; $i < $num_cache_hits; $i ++ )
    {
        $value = \Illuminate\Support\Str::random( 1000 );
        Cache::put( $cache_key, $value, 60 );
        $value = Cache::get( $cache_key );
    }

    // Cache (redis) IP address
    //
    $ip_address = request()->ip();
    $host_name = request()->server( 'HOSTNAME' );
    $name = $host_name;

    $cache_key = 'ip_addresses_98765';
    $cached_ip_addresses = Cache::get( $cache_key, [] );
    if(request()->has('clear_cache'))
    {
        $cached_ip_addresses = [];
        Cache::put( $cache_key, $cached_ip_addresses, 600 );
        return back();
    }
    if(! array_key_exists( $name, $cached_ip_addresses ) )
    {
        $cached_ip_addresses[$name] = 0;
    }
    $cached_ip_addresses[$name] ++;
    Cache::put( $cache_key, $cached_ip_addresses, 600 );
//dd($_SERVER);

    // Job (supervisor) load testing
    //
    $num_jobs = request()->get( 'job', 0 );
    for ( $i = 0; $i < $num_jobs; $i ++ )
    {
        $job = new TestJob();
        dispatch( $job );
    }

    // DB (mysql) load testing
    //
    $num_queries = request()->get( 'db', 1 );
    $places      = [];
    foreach ( range( 1, $num_queries ) as $i )
    {
        // random lat / lng ranges for places
        $random_latitude  = rand( - 90, 90 );
        $random_longitude = rand( - 180, 180 );

        $sql = <<<SQL
SELECT
    *,
    (
      6371 * acos (
      cos ( radians($random_latitude) )
      * cos( radians( latitude ) )
      * cos( radians( longitude ) - radians($random_longitude) )
      + sin ( radians($random_latitude) )
      * sin( radians( latitude ) )
    )
) AS distance_in_km
FROM places
ORDER BY distance_in_km
SQL;

        $places = DB::select( $sql );
    }
    $places = collect( $places )->take( 10 );

    return view( 'welcome', [
        'is_integrity_okay' => $is_integrity_okay,
        'places'            => $places,
        'random_latitude'   => $random_latitude,
        'random_longitude'  => $random_longitude,
        'cached_ip_addresses' => $cached_ip_addresses,
    ] );
} );

Route::get( '/dashboard', function () {
    return view( 'dashboard' );
} )->middleware( [ 'auth', 'verified' ] )->name( 'dashboard' );

Route::middleware( 'auth' )->group( function () {
    Route::get( '/profile', [ ProfileController::class, 'edit' ] )->name( 'profile.edit' );
    Route::patch( '/profile', [ ProfileController::class, 'update' ] )->name( 'profile.update' );
    Route::delete( '/profile', [ ProfileController::class, 'destroy' ] )->name( 'profile.destroy' );
} );

require __DIR__ . '/auth.php';
