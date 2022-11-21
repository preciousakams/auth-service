<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLandlordAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('landlord_agents', function (Blueprint $table) {
            $table->increments('id');
            $table->uuid('landlord_id');
            $table->uuid('agent_id');
            $table->boolean('has_accepted')->default(false);
            $table->boolean('can_create_property')->default(false);
            $table->boolean('create_property_request')->default(false);
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
        Schema::dropIfExists('landlord_agents');
    }
}
