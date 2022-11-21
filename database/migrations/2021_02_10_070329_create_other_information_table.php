<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOtherInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('other_information', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('marital_status')->nullable();
            $table->string('monthly_income')->nullable();
            $table->string('previous_known_name')->nullable();
            $table->string('occupation')->nullable();
            $table->string('employer_name')->nullable();
            $table->string('employer_email')->nullable();
            $table->string('employer_address')->nullable();
            $table->string('employer_phone')->nullable();
            $table->string('means_of_identification')->nullable();
            $table->string('means_of_identification_number')->nullable();
            $table->string('previous_property_manager_name')->nullable();
            $table->string('previous_property_manager_phone')->nullable();
            $table->string('previous_residential_address')->nullable();
            $table->string('emergency_contact_full_name')->nullable();
            $table->string('emergency_contact_phone')->nullable();
            $table->string('emergency_contact_relationship')->nullable();
            $table->string('emergency_contact_address')->nullable();
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
        Schema::dropIfExists('other_information');
    }
}
