<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('payments', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('user_id');
      $table->unsignedBigInteger('order_id');
      $table->unsignedBigInteger('package_id');
      $table->integer('current_price');
      $table->integer('regular_price')->nullable();
      $table->integer('subs_year')->nullable();
      $table->integer('subs_total')->nullable();
      $table->string('payment_method');
      $table->string('agent_account');
      $table->string('client_account');
      $table->string('transaction_no');
      $table->string('status');
      $table->timestamp('start_date')->nullable();
      $table->timestamp('end_date')->nullable();
      $table->unsignedBigInteger('approved_id')->nullable();
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
    Schema::dropIfExists('payments');
  }
}
