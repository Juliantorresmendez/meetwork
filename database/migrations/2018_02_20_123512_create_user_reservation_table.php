<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserReservationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          Schema::create('user_reservation', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('site_id');
            $table->integer('user_id');
            $table->integer('number_person');
            $table->date('date_reservation');
            $table->string('time', 100);
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
        //
    }
}
