<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTblStoragesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_storages', function (Blueprint $table) {
            $table->id();
            $table->integer('location_id');
            $table->integer('company_id');
            $table->string('company_name')->nullable();
            $table->string('company_owner')->nullable();
            $table->date('license_approved_date')->nullable();
            $table->date('license_renew_date')->nullable();
            $table->longtext('address')->nullable();
            $table->string('thana')->nullable();
            $table->string('distric')->nullable();
            $table->string('division')->nullable();
            $table->string('company_type')->nullable();
            $table->longtext('company_detils')->nullable();
            $table->longtext('storage_img')->nullable();
            $table->string('alert_tag')->nullable();
            $table->tinyInteger('status')->default('0');
            $table->integer('admin_id')->nullable();;
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
        Schema::dropIfExists('tbl_storages');
    }
}
