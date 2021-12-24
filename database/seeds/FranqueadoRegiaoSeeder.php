<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FranqueadoRegiaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('franqueado_regiao')->insert([
            "franqueado_id" => 1,
            "regiao_id" => 1,
            "usuario_sistema_admin_id" => 1,
            "status" => 'ativo',
        ]);
    }
}
