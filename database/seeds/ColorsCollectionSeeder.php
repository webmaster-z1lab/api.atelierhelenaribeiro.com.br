<?php

use Illuminate\Database\Seeder;
use Modules\Stock\Models\Color;

class ColorsCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Color::class)->create(['name' => 'Branco']);
        factory(Color::class)->create(['name' => 'Nude']);
        factory(Color::class)->create(['name' => 'Rose']);
        factory(Color::class)->create(['name' => 'Rosa']);
        factory(Color::class)->create(['name' => 'Salmão']);
        factory(Color::class)->create(['name' => 'Pink']);
        factory(Color::class)->create(['name' => 'Tiffany']);
        factory(Color::class)->create(['name' => 'Verde']);
        factory(Color::class)->create(['name' => 'Verde acqua']);
        factory(Color::class)->create(['name' => 'Esmeralda']);
        factory(Color::class)->create(['name' => 'Verde menta']);
        factory(Color::class)->create(['name' => 'Serenity']);
        factory(Color::class)->create(['name' => 'Azul claro']);
        factory(Color::class)->create(['name' => 'Azul bic']);
        factory(Color::class)->create(['name' => 'Azul marinho']);
        factory(Color::class)->create(['name' => 'Vermelho']);
        factory(Color::class)->create(['name' => 'Marsala']);
        factory(Color::class)->create(['name' => 'Preto']);
    }
}
