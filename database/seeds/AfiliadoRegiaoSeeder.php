<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AfiliadoRegiaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('afiliado_regiao')->insert([
            "afiliado_id" => 1,
            "regiao_id" => 1,
            "plano_assinatura_afiliado_regiao_id" => 1,
        ]);
    }
}
