<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableTrAgentFollowUp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tr_agent_follow_up', function (Blueprint $table) {
            $table->id();
            $table->integer('customer_id');
            $table->integer('agent_id');
            $table->integer('status');
            $table->string('remarks');
            $table->timestamps();
            $table->foreign('agent_id')->references('id')->on('users');
            $table->foreign('customer_id')->references('id')->on('ms_customer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tr_agent_follow_up');
    }
}
