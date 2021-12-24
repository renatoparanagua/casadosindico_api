<?php

namespace App\Providers;

use App\Models\Orcamento;
use App\Observers\OrcamentoObserver;
use Illuminate\Support\ServiceProvider;

class ObvserversProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        Orcamento::observe(OrcamentoObserver::class);
    }
}
