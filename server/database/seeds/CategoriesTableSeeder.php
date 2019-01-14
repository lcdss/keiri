<?php

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Category::create([
            'name' => 'Pessoal',
            'children' => [
                ['name' => 'Energia Elétrica'],
                ['name' => 'Casamento'],
                ['name' => 'Construção'],
            ],
        ]);

        Category::create([
            'name' => 'Investimento',
            'children' => [
                ['name' => 'Manutenção'],
                ['name' => 'Construção'],
            ],
        ]);
    }
}
