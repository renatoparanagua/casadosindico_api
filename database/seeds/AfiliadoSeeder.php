<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AfiliadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        DB::table('afiliado')->insert([
            'razao_social' => $faker->name,
            'nome_fantasia' => $faker->name,
            'email' => $faker->email(),
            'telefone' => $faker->randomNumber(9),
            'cnpj' => $faker->randomNumber(7),
            'cartao_cnpj' => $faker->randomNumber(7),
            'inscricao_estadual' => $faker->text(20),
            'inscricao_municipal' => $faker->text(20),
            'cep' => $faker->randomNumber(5),
            'usuario_app_id' => 1,
        ]);
    }
}
