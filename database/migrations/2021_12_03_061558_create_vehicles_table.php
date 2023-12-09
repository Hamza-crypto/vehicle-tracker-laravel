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
            $table->string('vin')->index();
            $table->string('purchase_lot')->nullable()->index();
            $table->string('auction_lot')->nullable()->index();
            $table->string('source')->nullable()->index();
            $table->string('location')->nullable()->index();
            $table->string('description')->nullable();
            $table->date('left_location')->nullable()->index();
            $table->date('date_paid')->nullable()->index();
            $table->integer('days_in_yard')->nullable()->index();
            $table->integer('invoice_amount')->nullable()->index();

            $table->timestamps();
            $table->softDeletes();
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