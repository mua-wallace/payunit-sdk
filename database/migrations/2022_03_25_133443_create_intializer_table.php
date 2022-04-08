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
            $table->id();
            $table->string('transaction_id')->unique();
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
