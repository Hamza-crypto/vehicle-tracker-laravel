<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('vin');
            $table->string('purchase_lot')->nullable();
            $table->string('auction_lot')->nullable();
            $table->string('source')->nullable();
            $table->string('location')->nullable();
            $table->string('description')->nullable();
            $table->date('left_location')->nullable();
            $table->date('date_paid')->nullable();
            $table->integer('invoice_amount')->nullable();

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
        Schema::dropIfExists('vehicles');
    }
}
