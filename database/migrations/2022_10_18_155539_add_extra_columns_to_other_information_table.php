<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraColumnsToOtherInformationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('other_information', function (Blueprint $table) {
            $table->string('name_of_next_of_kin')->nullable();
            $table->string('contact_of_next_kin')->nullable();
            $table->string('email_of_next_of_kin')->nullable();
            $table->string('next_of_kin_home_address')->nullable();
            $table->string('name_of_reference')->nullable();
            $table->string('contact_of_reference')->nullable();
            $table->string('signed_agreement_doc')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('other_information', function (Blueprint $table) {
            $table->dropColumn('name_of_next_of_kin');
            $table->dropColumn('contact_of_next_kin');
            $table->dropColumn('email_of_next_of_kin');
            $table->dropColumn('next_of_kin_home_address');
            $table->dropColumn('name_of_reference');
            $table->dropColumn('contact_of_reference');
            $table->dropColumn('signed_agreement_doc');
        });
    }
}
