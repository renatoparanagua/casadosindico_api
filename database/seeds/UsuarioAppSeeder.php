<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('usuario_app')->insert([
            'email' => 'eduardo@email.com',
            'senha' => Hash::make('password'),
            'tipo' => 'sindico',
        ]);
    }
}
