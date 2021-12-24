<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsuarioAppSeeder::class);
        // $this->call(RegiaoSeeder::class);
        // $this->call(SindicoSeeder::class);
        // $this->call(CondominioSeeder::class);
        // $this->call(AfiliadoSeeder::class);
        // $this->call(EstadoSeeder::class);
        // $this->call(CidadeSeeder::class);
        // $this->call(BairroSeeder::class);
        // $this->call(FranqueadoSeeder::class);
        // $this->call(UsuarioSistemaAdminSeeder::class);
        // $this->call(FranqueadoRegiaoSeeder::class);
        // $this->call(PlanoDisponivelFranqueadoSeeder::class);
        // $this->call(FranqueadoRegiaoPlanoDisponibilizadoSeeder::class);
        // $this->call(CategoriaSeeder::class);
        // $this->call(AfiliadoCategoriaSeeder::class);
        $this->call(PlanoAssinaturaAfiliadoRegiaoSeeder::class);
        $this->call(AfiliadoRegiaoSeeder::class);
    }
}
