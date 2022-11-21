<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_information', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('business_name');
            $table->string('business_registration_number');
            $table->longText('business_registration_address');
            $table->string('business_phone');
            $table->string('company_email');
            $table->string('state');
            $table->string('lga');
            $table->uuid('user_id');
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
        Schema::dropIfExists('business_information');
    }
}
