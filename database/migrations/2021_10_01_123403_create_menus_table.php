<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMenusTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('menu_group', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('name');
      $table->string('slug')->unique();
      $table->text('description')->nullable();
      $table->unsignedInteger('user_id');
      $table->timestamps();
      $table->softDeletes();
    });

    Schema::create('menus', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('name');
      $table->string('slug')->unique();
      $table->integer('parent_id');
      $table->unsignedInteger('menu_group_id');
      $table->unsignedInteger('user_id');
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
    Schema::dropIfExists('menu_group');
    Schema::dropIfExists('menus');
  }
}
