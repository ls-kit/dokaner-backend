<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->text("post_title");
            $table->string("post_slug", 255);
            $table->longText("post_content")->nullable();
            $table->text("post_excerpt")->nullable();
            $table->string("post_status", 45)->index();
            $table->timestamp("schedule_time")->nullable();
            $table->string("post_type", 55)->index();
            $table->string("post_format", 55)->default("standard");
            $table->string("post_thumb")->nullable();
            $table->string("thumb_directory")->nullable();
            $table->boolean("thumb_status")->default(1);
            $table->string("comments_status")->nullable();
            $table->string("attachment_status")->nullable();
            $table->string("option_status")->nullable();
            $table->integer("revision_by")->nullable();
            $table->integer("update_by")->nullable();
            $table->bigInteger("user_id");
            $table->integer("read_count")->default(0);
            $table->integer("comment_count")->default(0);
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
        Schema::dropIfExists('posts');
    }
}
