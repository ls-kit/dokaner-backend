<?php

use App\Models\Content\Taxonomy;
use Illuminate\Database\Seeder;

class TaxonomyTableSeeder extends Seeder
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
    $this->truncate('taxonomies');

    $csvFile = fopen(base_path("database/data/taxonomies.csv"), "r");
    $taxonomy = true;

    while (($data = fgetcsv($csvFile, 3500, ",")) !== FALSE) {
      if (!$taxonomy) {
        Taxonomy::create([
          "active" => checkNull($data['1']),
          "name" => checkNull($data['2']),
          "keyword" => checkNull($data['3']),
          "slug" => checkNull($data['4']),
          "description" => checkNull($data['5']),
          "ParentId" => checkNull($data['6']),
          "icon" => checkNull($data['7']),
          "picture" => checkNull($data['8']),
          "otc_id" => checkNull($data['9']),
          "ProviderType" => checkNull($data['10']),
          "IconImageUrl" => checkNull($data['11']),
          "ApproxWeight" => checkNull($data['12']),
          "is_top" => checkNull($data['13']),
          "user_id" => 1,
        ]);
      }
      $taxonomy = false;
    }

    fclose($csvFile);
    $this->enableForeignKeys();
  }
}
