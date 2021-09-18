<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentWalletsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_wallet', function (Blueprint $table) {
            $table->foreignId('wallet_id');
            $table->foreign('wallet_id')->references('id')->on('wallets');

            $table->foreignId('payment_id');
            $table->foreign('payment_id')->references('id')->on('payments');

            $table->foreignId('payment_type_id');
            $table->foreign('payment_type_id')->references('id')->on('payment_types');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_wallets');
    }
}
