<?php

use App\Models\Content\Setting;
use Illuminate\Database\Seeder;

class SettingTableSeeder extends Seeder
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
    $this->truncate('settings');

    $csvFile = fopen(base_path("database/data/settings.csv"), "r");
    $taxonomy = true;

    // id, active, key, value, user_id, created_at, updated_at
    while (($data = fgetcsv($csvFile, 3200, ",")) !== FALSE) {
      if (!$taxonomy) {
        Setting::create([
          "active" => now(),
          "key" => checkNull($data['2']),
          "value" => checkNull($data['3']),
          "user_id" => 1,
        ]);
      }
      $taxonomy = false;
    }

    fclose($csvFile);
    $this->enableForeignKeys();
  }
}
