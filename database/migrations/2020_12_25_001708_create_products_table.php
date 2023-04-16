<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('products', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamp('active')->nullable();
      $table->string('ItemId', 25);
      $table->string('ProviderType')->nullable();
      $table->text('Title')->nullable();
      $table->string('CategoryId')->nullable();
      $table->string('ExternalCategoryId')->nullable();
      $table->string('VendorId')->nullable();
      $table->string('VendorName')->nullable();
      $table->integer('VendorScore')->nullable();
      $table->text('PhysicalParameters')->nullable();
      $table->string('BrandId', 25)->nullable();
      $table->string('BrandName')->nullable();
      $table->string('TaobaoItemUrl')->nullable();
      $table->string('ExternalItemUrl')->nullable();
      $table->text('MainPictureUrl')->nullable();
      $table->text('Price')->nullable();
      $table->text('Pictures')->nullable();
      $table->text('Features')->nullable();
      $table->integer('MasterQuantity')->nullable();
      $table->unsignedInteger('user_id')->nullable();
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
    Schema::dropIfExists('products');
  }
}
