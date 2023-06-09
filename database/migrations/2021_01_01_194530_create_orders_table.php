<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('orders', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('order_number', 25)->nullable();
      $table->string('name')->nullable();
      $table->string('email')->nullable();
      $table->string('phone', 20)->nullable();
      $table->double('amount')->nullable();
      $table->double('needToPay')->nullable();
      $table->double('dueForProducts')->nullable();
      $table->text('address');
      $table->string('pay_method')->nullable();
      $table->string('status')->nullable();
      $table->string('transaction_id')->nullable();
      $table->string('refNumber')->nullable();
      $table->string('trxId')->nullable();
      $table->string('currency', 20)->nullable();
      $table->string('coupon_code', 55)->nullable();
      $table->double('coupon_victory')->nullable();
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
    Schema::dropIfExists('orders');
  }
}
