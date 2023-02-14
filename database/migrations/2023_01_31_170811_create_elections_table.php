<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->string('candidate');
            $table->string('votes_first_round')->nullable();
            $table->string('percentage_first_round')->nullable();
            $table->string('votes_second_round')->nullable();
            $table->string('percentage_second_round')->nullable();
            $table->string('party')->nullable();
            $table->string('department')->nullable();
            $table->string('year')->nullable();
            $table->string('election')->nullable();
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
        Schema::dropIfExists('meetings');
    }
};
