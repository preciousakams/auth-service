<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('referral_account')) {
            Schema::create('referral_account', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->decimal('balance', 15,2)->default(0.00);
                $table->integer('bank_id')->nullable();
                $table->integer('account_number')->nullable();
                $table->string('account_name')->nullable();
                $table->uuid('user_id');
                $table->softDeletes();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('referral_account');
    }
}
