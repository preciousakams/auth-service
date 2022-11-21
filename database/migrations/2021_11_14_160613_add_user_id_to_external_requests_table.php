<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserIdToExternalRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('external_requests', function (Blueprint $table) {
            Schema::table('external_requests', function (Blueprint $table) {
                $table->string('pay_ref')->nullable()->after('response');
                $table->string('user_id')->nullable()->after('url');
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('external_requests', function (Blueprint $table) {
            $table->dropColumn('pay_ref');
            $table->dropColumn('user_id');
        });
    }
}
