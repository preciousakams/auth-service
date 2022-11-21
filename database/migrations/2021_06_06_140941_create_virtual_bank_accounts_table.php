<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVirtualBankAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('virtual_bank_accounts')) {
            Schema::create('virtual_bank_accounts', function (Blueprint $table) {
                $table->id();
                $table->string('user_id');
                $table->string('account_name');
                $table->string('account_number');
                $table->bigInteger('bank_id')->unsigned();
                $table->enum('status', ['enabled', 'disabled']);
                $table->timestamps();
                $table->foreign('bank_id')->references('id')->on('banks');
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
        Schema::dropIfExists('virtual_bank_accounts');
    }
}
