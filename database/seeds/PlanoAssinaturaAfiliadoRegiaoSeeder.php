<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PlanoAssinaturaAfiliadoRegiaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('plano_assinatura_afiliado_regiao')->insert([
            "nome" => 'plano 1',
            "valor" => 10.0,
            "valor_comissao" => 0.0,
            "statusPlano" => '1',
            "quantidade_meses_vigencia" => '1',
            "franqueado_regiao_plano_disponibilizado_id" => 1,
        ]);
    }
}
