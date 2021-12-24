<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\AfiliadoOrcamentoInteresse as ResourcesAfiliadoOrcamentoInteresse;
use App\Models\Afiliado;
use App\Models\AfiliadoOrcamentoInteresse;
use App\Models\BO\OrcamentoBO;
use App\Models\Categoria;
use App\Models\Condominio;
use App\Models\ImagemOrcamento;
use App\Models\Notificacao;
use App\Models\Orcamento;
use App\Models\Sindico;
use App\Models\UsuarioApp;
use App\Models\Vistoria;
use App\Models\VistoriaImagem;
use App\Util\Formatacao;
use App\Util\StatusOrcamento;
use App\Util\StatusVistoria;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class AfiliadoOrcamentoInteresseController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request, new AfiliadoOrcamentoInteresse(), new Afiliado());
    }

    public function index($orcamento_id = null)
    {
        $this->newLog("Síndico solicitando afiliados interessados em um orçamento.");
        try {
            $dados = AfiliadoOrcamentoInteresse::where("orcamento_id", $orcamento_id)->where("descartado_afiliado", 0)->where("descartado_sindico", "<=", 0)->get();
            $res = [];
            foreach ($dados as $i => $d) {
                $dados[$i]['afiliado'] = $d->afiliado()->first();

                if ($dados[$i]['afiliado']) {
                    $dados[$i]['afiliado']["razao_social"] = $dados[$i]['afiliado']["nome_fantasia"] ? $dados[$i]['afiliado']["nome_fantasia"] : $dados[$i]['afiliado']["razao_social"];
                    //Verificar se o afiliado está com plano ativo e pago
                    $res[] = $dados[$i];
                }
            }

            return $this->successResponse('Sucesso!', $res);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $id)
    {
        $this->newLog("Afiliado interagiu com um orçamento. Demonstrando interesse ou não pelo orçamento.");
        try {

            $afiliadoInteresse = AfiliadoOrcamentoInteresse::where("orcamento_id", $id)->where("afiliado_id", $this->usuario_tipo_id)->get();
            foreach ($afiliadoInteresse as $a) {
                $a->delete();
            }


            $afiliado_orcamento_interesse = new AfiliadoOrcamentoInteresse();
            $afiliado_orcamento_interesse->afiliado_id = $this->usuario_tipo_id;
            $afiliado_orcamento_interesse->orcamento_id = $id;
            $afiliado_orcamento_interesse->descartado_afiliado = $request['descartado_afiliado'];
            $afiliado_orcamento_interesse->save();

            $orcamento = Orcamento::where("id", $afiliado_orcamento_interesse->orcamento_id)->first();
            $orcamento->data_atualizacao = Carbon::now();
            $orcamento->update();

            $data = new ResourcesAfiliadoOrcamentoInteresse($afiliado_orcamento_interesse);

            $solicitacao = Orcamento::where("id", $id)->first();
            $condominio = Condominio::withTrashed()->where("id", $solicitacao->condominio_id)->first();
            $sindico = Sindico::withTrashed()->where("id", $condominio->sindico_id)->first();
            $usuarioApp = UsuarioApp::withTrashed()->where("id", $sindico->usuario_app_id)->first();

            $afiliado = Afiliado::withTrashed()->where("id", $afiliado_orcamento_interesse->afiliado_id)->first();

            if ($afiliado_orcamento_interesse->descartado_afiliado == 0) {
                Notificacao::painelNotificarSindicioNovoInteressdo($orcamento, $sindico, $afiliado);
                if ($usuarioApp->token_notification) {
                    SenderNotificacao::novoInteressado($orcamento->id, $usuarioApp->token_notification, $afiliado->razao_social);
                }
                SenderEmails::enviarEmailNovoInteressado($usuarioApp->email, $sindico->nome, $afiliado->razao_social, $orcamento->id);
            }

            return $this->successResponse('Afiliado orçamento interesse created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }


    // public function update(Request $request, $id)
    // {
    //     $this->newLog("Síndico aceitou uma orçamento de afiliado.");
    //     try {
    //         $afiliado_orcamento_interesse = AfiliadoOrcamentoInteresse::findOrFail($id);
    //         $afiliado_orcamento_interesse->afiliado_id = $request['afiliado_id'];
    //         $afiliado_orcamento_interesse->orcamento_id = $request['orcamento_id'];
    //         $afiliado_orcamento_interesse->interessado = $request['interessado'];
    //         $afiliado_orcamento_interesse->descartado_afiliado = $request['descartado_afiliado'];

    //         $afiliado_orcamento_interesse->update();

    //         $orcamento = Orcamento::where("id", $afiliado_orcamento_interesse->orcamento_id)->first();
    //         $orcamento->data_atualizacao = Carbon::now();
    //         $orcamento->update();

    //         $data = new ResourcesAfiliadoOrcamentoInteresse($afiliado_orcamento_interesse);

    //         if ($request['descartado_afiliado'] == 0) {
    //             $solicitacao = Orcamento::where("id", $id)->first();
    //             $condominio = Condominio::where("id", $solicitacao->condominio_id)->first();
    //             $sindico = Sindico::where("id", $condominio->sindico_id)->first();


    //             $afiliado = Afiliado::where("id", $afiliado_orcamento_interesse->afiliado_id)->first();
    //             $usuarioApp = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();

    //             if ($usuarioApp->token_notification) {
    //                 SenderNotificacao::aceitarOrcamento($orcamento->id . " - " . $solicitacao->nome, $usuarioApp->token_notification, $sindico->nome, $solicitacao);
    //             }
    //             SenderEmails::enviarEmailAceitarOrcamento($usuarioApp->email, $sindico->nome, $sindico->nome, $orcamento->id);
    //         }

    //         return $this->successResponse('Afiliado orcamento interesse updated!', $data);
    //     } catch (Exception $e) {
    //         return $this->errorResponse('Error processing your request');
    //     }
    // }

    public function partial_update(Request $request, $id)
    {
        $this->newLog("Síndico interagiu com um orçamento, aceitando ou rejeitando orçamento de afiliados.");

        try {
            $afiliado_orcamento_interesse = AfiliadoOrcamentoInteresse::findOrFail($id);
            $afiliado_orcamento_interesse->descartado_sindico = $request['descartado_sindico'];
            $afiliado_orcamento_interesse->update();

            $orcamento = Orcamento::where("id", $afiliado_orcamento_interesse->orcamento_id)->first();
            $orcamento->data_atualizacao = Carbon::now();
            $orcamento->update();

            $solicitacao = OrcamentoBO::transform($orcamento, "sindico", $this->usuario_tipo_id);

            $condominio = Condominio::withTrashed()->where("id", $solicitacao->condominio_id)->first();
            $sindico = Sindico::where("id", $condominio->sindico_id)->first();

            $afiliado = Afiliado::where("id", $afiliado_orcamento_interesse->afiliado_id)->first();
            $usuarioApp = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
            if ($afiliado && $usuarioApp && $sindico) {
                if ($request['descartado_afiliado'] == 0 && $request['descartado_sindico'] == 0) {
                    Notificacao::painelNotificarAfiliadoSindicoAceita($orcamento, $sindico, $afiliado);
                    if ($usuarioApp->token_notification) {
                        SenderNotificacao::aceitarOrcamento($orcamento->id . " - " . $solicitacao->nome, $usuarioApp->token_notification, $sindico->nome, $solicitacao);
                    }
                    SenderEmails::enviarEmailAceitarOrcamento($usuarioApp->email, $afiliado->razao_social, $sindico->nome, $orcamento->id . " - " . $solicitacao->nome);
                } else if ($request['descartado_sindico'] == 1) {
                    Notificacao::painelNotificarAfiliadoSindicoRecusa($orcamento, $sindico, $afiliado);
                    if ($usuarioApp->token_notification) {
                        SenderNotificacao::recusarOrcamento($orcamento->id . " - " . $solicitacao->nome, $usuarioApp->token_notification, $solicitacao);
                    }
                    SenderEmails::enviarEmailRecusarOrcamento($usuarioApp->email, $afiliado->razao_social, "", $orcamento->id . " - " . $solicitacao->nome);
                }
            }


            $data = new ResourcesAfiliadoOrcamentoInteresse($afiliado_orcamento_interesse);
            return $this->successResponse('Afiliado orcamento interesse atualizado', $solicitacao);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse($e->getCode());
        }
    }
}
