<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSearchLogsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('search_logs', function (Blueprint $table) {
      $table->bigIncrements('id');
      $table->string('search_id');
      $table->string('search_type', 55)->default('text');
      $table->string('query_data', 400)->nullable();
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
    Schema::dropIfExists('search_logs');
  }
}
