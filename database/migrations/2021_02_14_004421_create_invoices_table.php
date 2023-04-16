<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('invoice_no')->nullable();
            $table->string('transaction_id');
            $table->string('customer_name')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->double('total_payable');
            $table->double('total_courier');
            $table->string('payment_method')->nullable();
            $table->string('delivery_method')->nullable();
            $table->double('total_due');
            $table->string('status')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('invoice_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('invoice_id');
            $table->unsignedBigInteger('order_item_id');
            $table->string('order_item_number');
            $table->unsignedBigInteger('product_id');
            $table->string('product_name', 300);
            $table->string('weight');
            $table->double('total_due');
            $table->unsignedBigInteger('user_id');
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
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_items');
    }
}
