<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomerCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ItemId');
            $table->timestamp('buy_status')->nullable();
            $table->string('ActualWeightInfo')->nullable();
            $table->text('QuantityRanges')->nullable();
            $table->text('Item')->nullable();
            $table->text('ItemData')->nullable();
            $table->integer('minQuantity')->nullable();
            $table->integer('localDelivery')->nullable();
            $table->string('shipped_by')->nullable();
            $table->integer('shippingRate')->nullable();
            $table->double('approxWeight')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
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
        Schema::dropIfExists('customer_carts');
    }
}
