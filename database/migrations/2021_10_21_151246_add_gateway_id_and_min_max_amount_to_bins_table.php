<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddGatewayIdAndMinMaxAmountToBinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bins', function (Blueprint $table) {
            $table->unsignedBigInteger('gateway_id')->nullable()->after('number');
            $table->unsignedBigInteger('min_amount')->after('gateway_id');
            $table->unsignedBigInteger('max_amount')->after('min_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bins', function (Blueprint $table) {
            //
        });
    }
}
