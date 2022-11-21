<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMonthlyIncomeToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('dob', 'date_of_birth');
            $table->decimal('monthly_income', 15, 2)->default(0.00)->after('marital_status');
            $table->string('number_of_children')->after('monthly_income')->nullable();
            $table->string('office_address')->after('number_of_children')->nullable();
            $table->string('place_of_business')->after('office_address')->nullable();
            $table->string('previous_property_manager')->after('place_of_business')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}
