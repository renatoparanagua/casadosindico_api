<?php

use Faker\Generator as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsuarioSistemaAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker $faker)
    {
        DB::table('usuario_sistema_admin')->insert([
            "nome" => $faker->name(),
            "email" => $faker->email(),
            "senha" => Hash::make('password'),
            "status" => '1',
            "tipo" => 'admin',
        ]);
    }
}
