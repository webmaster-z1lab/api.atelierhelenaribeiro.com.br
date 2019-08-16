<?php

use Illuminate\Database\Seeder;

class ColorsCollectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \Modules\Stock\Models\Color::insert([
            ['name' => 'Branco'],
            ['name' => 'Nude'],
            ['name' => 'Rose'],
            ['name' => 'Rosa'],
            ['name' => 'Salmão'],
            ['name' => 'Pink'],
            ['name' => 'Tiffany'],
            ['name' => 'Verde'],
            ['name' => 'Verde acqua'],
            ['name' => 'Esmeralda'],
            ['name' => 'Verde menta'],
            ['name' => 'Serenity'],
            ['name' => 'Azul claro'],
            ['name' => 'Azul bic'],
            ['name' => 'Azul marinho'],
            ['name' => 'Vermelho'],
            ['name' => 'Marsala'],
            ['name' => 'Preto'],
        ]);
    }
}
