<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSitesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 100);
            $table->string('admin', 100);
            $table->string('phone',100);
            $table->string('email', 100);
            $table->string('address', 100);
            $table->integer('city');
            $table->string('lat', 100);
            $table->string('lon', 100);
            $table->integer('type');
            $table->string('meta', 255);
            $table->string('services', 255);
            $table->text('description');
            $table->timestamps();

            //ToDo: Ver si relacionar con foreing key RegionId, CountryId y SponsorId
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
               Schema::dropIfExists('sites');

    }
}
