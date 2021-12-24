<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BairroSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('bairro')->insert([
            "nome" => 'Coqueiros',
            "chave" => 'coq-flr-sc',
            "cidade_id" => 1,
        ]);
    }
}
