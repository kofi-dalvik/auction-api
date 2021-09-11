<?php

namespace Database\Seeders;

use DB;
use Schema;
use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Schema::disableForeignKeyConstraints();
        DB::table('items')->truncate();
        DB::table('item_images')->truncate();
        Schema::enableForeignKeyConstraints();

        $items = json_decode(file_get_contents(__DIR__.'/data/items.json'), true);

        foreach ($items as $data) {
            $item = Item::factory()->create([
                'name' => $data['name'],
                'description' => $data['description']
            ]);

            $item->images()->createMany(array_map(fn ($image) => ['url' => $image], $data['images']));
        }
    }
}
