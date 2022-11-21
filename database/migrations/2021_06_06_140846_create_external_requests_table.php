<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExternalRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('external_requests')) {
            Schema::create('external_requests', function (Blueprint $table) {
                $table->bigIncrements('id');
                $table->string('service')->nullable();
                $table->string('endpoint')->nullable();
                $table->string('url')->nullable();
                $table->string('reference')->nullable();
                $table->text('request')->nullable();
                $table->text('response')->nullable();
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
        Schema::dropIfExists('external_requests');
    }
}
