<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FranqueadoRegiaoPlanoDisponibilizadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('franqueado_regiao_plano_disponibilizado')->insert([
            "franqueado_regiao_id" => 1,
            "plano_disponivel_franqueado_id" => 1,
        ]);
    }
}
