<?php

namespace App\Jobs;

use App\Http\Controllers\Api\SenderEmails;
use App\Models\Notificacao;
use App\Models\Orcamento;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessNotificacao implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $orcamento;
    public function __construct($orcamento)
    {
        $this->orcamento = $orcamento;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //SenderEmails::senderEnviarEmailAfiliados($this->orcamento);
        //SenderEmails::sendEmailAdminsAndFranqueados($this->orcamento);
    }
}
