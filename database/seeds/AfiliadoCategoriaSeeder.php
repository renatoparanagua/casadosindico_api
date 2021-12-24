<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AfiliadoCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('afiliado_categoria')->insert([
            "afiliado_id" => 1,
            "categoria_id" => 1,
        ]);
    }
}
