<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCouponsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('coupons', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamp('active')->nullable();
      $table->string('coupon_code')->unique();
      $table->string('coupon_type')->nullable(); // will be flat or percentage
      $table->double('coupon_amount')->nullable();
      $table->double('minimum_spend')->nullable();
      $table->double('maximum_spend')->nullable();
      $table->integer('limit_per_coupon')->nullable();
      $table->integer('limit_per_user')->nullable();
      $table->timestamp('expiry_date')->nullable();
      $table->unsignedBigInteger('user_id');
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::create('coupon_user', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('coupon_id');
      $table->string('coupon_code');
      $table->string('coupon_details', 255)->nullable();
      $table->integer('win_amount')->nullable();
      $table->unsignedBigInteger('order_id');
      $table->unsignedBigInteger('user_id'); // will be customer id
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
    Schema::dropIfExists('coupons');
    Schema::dropIfExists('coupon_user');
  }
}
