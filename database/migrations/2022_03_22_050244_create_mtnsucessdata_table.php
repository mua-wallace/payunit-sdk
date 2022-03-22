<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMtnsucessdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mtnsucessdata', function (Blueprint $table) {
            $table->id();
            $table->string('gateway');
            $table->string('status');
            $table->text('message');
            $table->string('payment_ref');
            $table->string('transaction_id')->unique();
            $table->text('pay_token');
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
        Schema::dropIfExists('mtnsucessdata');
    }
}
