<?php

use App\Demo\Utilities\IntegrityChecker;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get( '/', function () {

    Log::info( 'Running integrity checker' );

    $integrity_checker = new IntegrityChecker( realpath( app_path() . '/..' ) );

    $integrity_checker->verify( false );

    $is_integrity_okay = $integrity_checker->isOkay();

    // random db query for places
    //
    $num_queries = request()->get( 'num_queries', 1 );
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
LIMIT 0, 10;
SQL;

        $places = DB::select( $sql );
    }

    return view( 'welcome', [
        'is_integrity_okay' => $is_integrity_okay,
        'places'            => $places,
        'random_latitude'   => $random_latitude,
        'random_longitude'  => $random_longitude,
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
