<?php

namespace App\Observers;

use App\Jobs\ProcessNotificacao;
use App\Models\Orcamento;

class OrcamentoObserver
{
    /**
     * Handle the Orcamento "created" event.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return void
     */
    public function created(Orcamento $orcamento)
    {
        ProcessNotificacao::dispatch($orcamento)->afterResponse();
    }


    /**
     * Handle the Orcamento "updated" event.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return void
     */
    public function updated(Orcamento $orcamento)
    {
        //
    }

    /**
     * Handle the Orcamento "deleted" event.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return void
     */
    public function deleted(Orcamento $orcamento)
    {
        //
    }

    /**
     * Handle the Orcamento "restored" event.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return void
     */
    public function restored(Orcamento $orcamento)
    {
        //
    }

    /**
     * Handle the Orcamento "force deleted" event.
     *
     * @param  \App\Models\Orcamento  $orcamento
     * @return void
     */
    public function forceDeleted(Orcamento $orcamento)
    {
        //
    }
}
