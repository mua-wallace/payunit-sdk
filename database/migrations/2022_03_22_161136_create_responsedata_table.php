<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsedataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responsedata', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->string('status');
            $table->string('message');

            $table->mediumText('payment_ref')->nullable();
            $table->mediumText('pay_token')->nullable();
            $table->string('gateway')->nullable();

            $table->mediumText('auth_token')->nullable();
            $table->mediumText('paytoken')->nullable();
            $table->mediumText('x-token')->nullable();

            $table->string('provider_short_tag')->nullable();
            $table->string('provider_logo')->nullable();


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
        Schema::dropIfExists('responsedata');
    }
}
