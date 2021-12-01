<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMetasTable extends Migration
{
    public function up()
    {
        Schema::create('user_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('meta_key');
            $table->text('meta_value');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_metas');
    }
}
