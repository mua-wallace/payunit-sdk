<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateIntializerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('initializer', function (Blueprint $table) {
            $table->string('transaction_id')->primary();
            $table->integer('total_amount');
            $table->string('name')->nullable();
            $table->mediumText('description')->nullable();
            $table->mediumText('return_url');
            $table->mediumText('notify_url')->nullable();
            $table->mediumText('purchaseRef')->nullable();
            $table->string('currency');
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
        Schema::dropIfExists('initializer');
    }
}
