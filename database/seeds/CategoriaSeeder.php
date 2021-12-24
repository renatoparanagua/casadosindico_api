<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        DB::table('categoria')->insert([
            "nome" => $faker->word(),
            "descricao" => $faker->text(),
            "chave_url" => $faker->text(10),
            "imagem" => $faker->image($dir = '/tmp', $width = 640, $height = 480),
        ]);
    }
}
