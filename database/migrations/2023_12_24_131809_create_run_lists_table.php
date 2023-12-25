<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('run_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('item_number');
            $table->string('lot_number');
            $table->string('claim_number');
            $table->string('description');
            $table->string('number_of_runs');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('run_lists');
    }
};