<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblAlertsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_alerts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('latitude')->nullable();
            $table->string('longtude')->nullable();
            $table->date('time')->nullable();
            $table->longtext('alert_type')->nullable();
            $table->integer('fair_station_id');
            $table->tinyInteger('status')->default('1');
            $table->date('reset_time')->nullable();
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
        Schema::dropIfExists('tbl_alerts');
    }
}
