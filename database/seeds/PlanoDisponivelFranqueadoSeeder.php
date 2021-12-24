<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanoDisponivelFranqueadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plano_disponivel_franqueado')->insert([
            "nome" => 'plano 1',
            "valor" => 10.0,
            "valor_comissao" => 0.0,
            "statusPlano" => '1',
            "quantidade_meses_vigencia" => '1',
            "usuario_sistema_admin_id" => 1,
            "regiao_id" => 1,
        ]);
    }
}
