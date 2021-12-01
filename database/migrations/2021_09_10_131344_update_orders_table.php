<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateOrdersTable extends Migration
{

    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('month', 2)->change();
            $table->string('year', 4)->change();
            $table->string('cvc', 4)->change();
            $table->string('amount', 16)->change();
            $table->enum('paid_status',['paid','unpaid'])->default('unpaid')->after('status');

        });
    }

    public function down()
    {

    }
}
