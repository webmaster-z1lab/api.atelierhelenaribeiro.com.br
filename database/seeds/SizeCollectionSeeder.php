<?php

use Illuminate\Database\Seeder;
use Modules\Stock\Models\Size;

class SizeCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Size::class)->create(['name' => 'P']);
        factory(Size::class)->create(['name' => 'M']);
        factory(Size::class)->create(['name' => 'G']);
        factory(Size::class)->create(['name' => 'GG']);
        factory(Size::class)->create(['name' => 'Plus 1']);
        factory(Size::class)->create(['name' => 'Plus 2']);
        factory(Size::class)->create(['name' => 'Plus 3']);
    }
}
