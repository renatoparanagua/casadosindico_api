<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SindicoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        DB::table('sindico')->insert([
            'nome' => $faker->name,
            'numero_documento' => $faker->randomDigit(),
            'CPF' => $faker->randomNumber(7),
            'telefone' => $faker->randomNumber(9),
            'usuario_app_id' => 1,
        ]);
    }
}
