<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create( 'places', function ( Blueprint $table ) {
            $table->id();
            $table->decimal( 'latitude', 10, 8 );
            $table->decimal( 'longitude', 11, 8 );
            $table->string( 'name' );
            $table->float( 'average_temperature' );
            $table->timestamps();
        } );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists( 'places' );
    }
};
