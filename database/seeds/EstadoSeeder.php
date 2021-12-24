<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('estado')->insert([
            "nome" => 'Santa Catarina',
            "chave" => 'flr-sc',
            "uf" => 'SC',
        ]);
    }
}
