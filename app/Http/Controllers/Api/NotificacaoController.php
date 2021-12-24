<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Parceiro as ResourcesParceiro;
use App\Models\Notificacao;
use App\Models\Parceiro;
use App\Util\Formatacao;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class NotificacaoController extends Controller
{

    public function __construct(Request $request)
    {

        parent::__construct($request, new Notificacao(), null);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->newLog("Listando notificações");
        try {
            $notificacoesAux = Notificacao::where("usuario_app_id", $this->user->id)->where("removido_app", null)->orderBy("id", "desc")->get();
            Notificacao::where("usuario_app_id", $this->user->id)->where("removido_app", null)->update([
                "data_visualizacao" => Carbon::now()
            ]);
            $notificacoes = [];
            foreach ($notificacoesAux as $notifi) {
                if ($notifi->tipo_usuario == $this->user->tipo || $notifi->tipo_usuario == null) {
                    $notifi->data_cadastro = Formatacao::data($notifi->data_cadastro);
                    $notificacoes[] = $notifi;
                }
            }
            return $this->successResponse('Success', $notificacoes);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Parceiro  $parceiro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        $this->newLog("Removendo notificação");
        try {
            $parceiro = Notificacao::findOrFail($id);
            $parceiro->removido_app = Carbon::now();
            $parceiro->update();
            return $this->successResponse('Parceiro deleted!', true);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function destroyAll()
    {
        $this->newLog("Removendo todas as notificações");
        try {
            Notificacao::where("usuario_app_id", $this->user->id)->where("removido_app", null)->update([
                "removido_app" => Carbon::now()
            ]);
            return $this->successResponse('Parceiro deleted!', true);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }


    public function naolidas()
    {
        $this->newLog("Contando notificações não lidas");
        try {
            $notificacoesAux = Notificacao::where("usuario_app_id", $this->user->id)->where("removido_app", null)->where("data_visualizacao", null)->get();
            $notificacoes = [];
            foreach ($notificacoesAux as $notifi) {
                if ($notifi->tipo_usuario == $this->user->tipo || $notifi->tipo_usuario == null) {
                    $notificacoes[] = $notifi;
                }
            }
            return $this->successResponse('Success', count($notificacoes));
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
