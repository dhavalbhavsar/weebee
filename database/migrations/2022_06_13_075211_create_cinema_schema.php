<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */
    public function up()
    {
        Schema::create('theaters', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('movies', function($table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('theater_id')->unsigned();
            $table->foreign('theater_id')->references('id')->on('theaters')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('shows', function($table) {
            $table->increments('id');
            $table->datetime('start');
            $table->datetime('end');
            $table->integer('movie_id')->unsigned();
            $table->foreign('movie_id')->references('id')->on('movies')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('seats', function($table) {
            $table->increments('id');
            $table->string('raw_name')->comment('Raw name like A, B, C, D'); 
            $table->integer('seats')->comment('Like raw A have 10 seats so here 10 will be placed.');
            $table->timestamps();
            $table->softDeletes();
        });


        Schema::create('prices', function($table) {
            $table->increments('id');
            $table->float('price', 8, 2);
            $table->integer('seat_id')->unsigned();
            $table->foreign('seat_id')->references('id')->on('seats')->onDelete('cascade');
            $table->integer('show_id')->unsigned();
            $table->foreign('show_id')->references('id')->on('shows')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('bookings', function($table) {
            $table->increments('id');
            $table->integer('seat_number')->comment('Like if raw A have 10 seats then we consider 1-10');
            $table->integer('price_id')->unsigned();
            $table->foreign('price_id')->references('id')->on('prices')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('theaters');
        Schema::dropIfExists('movies');
        Schema::dropIfExists('shows');
        Schema::dropIfExists('seats');
        Schema::dropIfExists('bookings');
    }
}
