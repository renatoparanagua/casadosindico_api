<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CondominioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        DB::table('condominio')->insert([
            "nome" => "Maria Carolina",
            "cep" => "88080080",
            "bairro" => "Coqueiro",
            "endereco" => "Rua Jau Guedes da Fonseca",
            "numero" => "292",
            "complemento" => "apto 101 bloco b",
            "sindico_id" => 1,
            "regiao_id" => 1,
        ]);
    }
}
