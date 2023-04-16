<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTaxonomiesTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('taxonomies', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->timestamp('active')->nullable();
      $table->string('name', 255);
      $table->string('keyword', 255)->nullable();
      $table->string('slug');
      $table->text('description')->nullable();
      $table->string('ParentId')->nullable();
      $table->string('icon', 255)->nullable();
      $table->string('picture', 255)->nullable();
      $table->string('otc_id', 191)->uniqid()->nullable();
      $table->string('ProviderType')->nullable();
      $table->string('IconImageUrl')->nullable();
      $table->string('ApproxWeight')->nullable();
      $table->timestamp('is_top')->nullable();
      $table->unsignedBigInteger('user_id');
      $table->timestamps();
      $table->softDeletes();

      // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });

    Schema::create('post_taxonomy', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('taxonomy_id');
      $table->unsignedBigInteger('post_id');
      $table->timestamps();
      $table->unique(['taxonomy_id', 'post_id']);
    });

    Schema::create('product_taxonomy', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->unsignedBigInteger('taxonomy_id');
      $table->unsignedBigInteger('CategoryId');
      $table->timestamps();
      $table->unique(['taxonomy_id', 'CategoryId']);
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('taxonomies');
    Schema::dropIfExists('post_taxonomy');
  }
}
