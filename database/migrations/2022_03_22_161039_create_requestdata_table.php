<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requestdata', function (Blueprint $table) {
            // $table->id();
            $table->string('transaction_id')->primary();
            $table->string('total_amount')->nullable();
            $table->string('currency')->nullable();
            $table->mediumText('return_url')->nullable();
            $table->mediumText('notify_url')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->mediumText('purchaseRef')->nullable();
            $table->string('gateway')->nullable();
            $table->integer('phone')->nullable();
            $table->string('hgsxvcdb')->nullable();
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
        Schema::dropIfExists('requestdata');
    }
}
