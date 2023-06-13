<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('shop');
            $table->string('price');
            $table->tinyInteger('is_pro')->default(0);

            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
