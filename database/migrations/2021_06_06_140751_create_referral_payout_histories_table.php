<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReferralPayoutHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('referral_payout_histories')) {
            Schema::create('referral_payout_histories', function (Blueprint $table) {
                $table->uuid('id')->primary();
                $table->uuid('refferral_id');
                $table->decimal('amount', '15','2');
                $table->string('transaction_status');
                $table->string('transaction_reference');
                $table->string('transaction_gateway');
                $table->longText('transaction_details');
                $table->string('admin_id');
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
        Schema::dropIfExists('referral_payout_histories');
    }
}
