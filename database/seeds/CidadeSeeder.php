<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('cidade')->insert([
            "nome" => 'Florianopolis',
            "chave" => 'flr-sc',
            "estado_id" => 1,
        ]);
    }
}
