<?php

use App\Models\Content\Post;
use Illuminate\Database\Seeder;

class PostTableSeeder extends Seeder
{
  use DisableForeignKeys, TruncateTable;
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run()
  {
    $this->disableForeignKeys();
    $this->truncate('posts');

    $csvFile = fopen(base_path("database/data/posts.csv"), "r");
    $post = true;
    while (($data = fgetcsv($csvFile, 2000, ",")) !== FALSE) {
      if (!$post) {
        Post::create([
          "post_title" => $data['0'],
          "post_slug" => $data['1'],
          "post_content" => $data['2'],
          "post_excerpt" => $data['3'],
          "post_status" => $data['4'],
          "schedule_time" => null,
          "post_type" => $data['6'],
          "post_format" => $data['7'],
          "post_thumb" => $data['8'],
          "thumb_directory" => $data['9'],
          "thumb_status" => $data['10'],
          "comments_status" => $data['11'],
          "attachment_status" => $data['12'],
          "option_status" => $data['13'],
          "user_id" => 1,
        ]);
      }
      $post = false;
    }

    fclose($csvFile);
    $this->enableForeignKeys();
  }
}
