<?php

use App\Demo\Utilities\IntegrityChecker;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;

Route::get( '/', function () {

    Log::info( 'Running integrity checker' );

    $integrity_checker = new IntegrityChecker( realpath( app_path() . '/..' ) );

    $integrity_checker->verify( true );

    $is_integrity_okay = $integrity_checker->isOkay();

    return view( 'welcome', [
        'is_integrity_okay' => $is_integrity_okay
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
