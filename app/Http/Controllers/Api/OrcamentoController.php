<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;

use App\Http\Resources\Orcamento as ResourcesOrcamento;
use App\Jobs\ProcessNotificacao;
use App\Models\Afiliado;
use App\Models\AfiliadoCategorium;
use App\Models\AfiliadoOrcamentoInteresse;
use App\Models\AfiliadoRegiao;
use App\Models\Bairro;
use App\Models\BO\OrcamentoBO;
use App\Models\Categoria;
use App\Models\Cidade;
use App\Models\Condominio;
use App\Models\Configuracao;
use App\Models\ContratoAssinatura;
use App\Models\Estado;
use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\ImagemOrcamento;
use App\Models\Logsendinblue;
use App\Models\Notificacao;
use App\Models\Orcamento;
use App\Models\OrcamentoAssinatura;
use App\Models\PlanoAssinaturaAfiliadoRegiao;
use App\Models\RegiaoFaixaCep;
use App\Models\Sindico;
use App\Models\UsuarioApp;
use App\Models\Vistoria;
use App\Models\Vistoriador;
use App\Models\VistoriaImagem;
use App\Util\Autentique\DocumentosAutentique;
use App\Util\Formatacao;
use App\Util\StatusOrcamento as UtilStatusOrcamento;
use App\Util\StatusPlano;
use App\Util\StatusVistoria;
use App\Util\Util;
use App\Util\Validacao;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use StatusOrcamento;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request as RequestPsr7;

class OrcamentoController extends Controller
{


    public function __construct(Request $request)
    {
        if ($this->usuario_logado) {
            if ($this->usuario_logado->tipo == "sindico") {
                parent::__construct($request, new Sindico());
            } elseif ($this->usuario_logado->tipo == "afiliado") {
                parent::__construct($request, new Afiliado());
            }
        }
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index()
    {
        $this->newLog("Síndico listando solicitações.");
        try {
            $orcamentos = [];
            if ($this->usuario_logado->tipo == "sindico") {
                $sindico = Sindico::where("usuario_app_id", $this->usuario_logado->id)->first();
                if ($sindico) {
                    $condominios = $sindico->condominios()->withTrashed()->get();
                    $condominiosID = [];
                    foreach ($condominios as $condominio) {
                        $condominiosID[] = $condominio->id;
                    }
                    $orcamentos_aux = Orcamento::where("modo", Util::getModusOperandi())->whereIn("condominio_id", $condominiosID)->orderBy("id", "DESC")->get();
                    foreach ($orcamentos_aux as $orc) {
                        $orcamentos[] = OrcamentoBO::transformList($orc, $this->usuario_logado->tipo, $this->usuario_tipo_id);
                    }
                } else {
                    return $this->errorResponse([Validacao::getError("Sua conta foi removida. Contate os administradores.", "inexistente")], 403);
                }
            }

            if ($orcamentos) {
                $orcamentosAux = (array) Formatacao::array_sort($orcamentos, 'id', SORT_ASC);
                $orcamentos = [];
                foreach ($orcamentosAux as $o) {
                    $orcamentos[] = $o;
                }
            }

            return $this->successResponse('Success', $orcamentos);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function indexPaginate($page)
    {
        $quantidade_itens = 10;
        $this->newLog("Síndico listando solicitações.");
        try {
            $orcamentos = [];
            if ($this->usuario_logado->tipo == "sindico") {
                $sindico = Sindico::where("usuario_app_id", $this->usuario_logado->id)->first();
                if ($sindico) {
                    $condominios = $sindico->condominios()->withTrashed()->get();
                    $condominiosID = [];
                    foreach ($condominios as $condominio) {
                        $condominiosID[] = $condominio->id;
                    }
                    $orcamentos_aux = Orcamento::where("modo", Util::getModusOperandi())->whereIn("condominio_id", $condominiosID)->orderBy("id", "DESC")->offset(($page - 1) * $quantidade_itens)->limit($quantidade_itens)->get();
                    foreach ($orcamentos_aux as $orc) {
                        $orcamentos[] = OrcamentoBO::transformList($orc, $this->usuario_logado->tipo, $this->usuario_tipo_id);
                    }
                } else {
                    return $this->errorResponse([Validacao::getError("Sua conta foi removida. Contate os administradores.", "inexistente")], 403);
                }
            }

            if ($orcamentos) {
                $orcamentosAux = (array) Formatacao::array_sort($orcamentos, 'id', SORT_ASC);
                $orcamentos = [];
                foreach ($orcamentosAux as $o) {
                    $orcamentos[] = $o;
                }
            }

            return $this->successResponse('Success', $orcamentos);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $orcamentoAux = new Orcamento();
        $nome = $this->getValueRequest($request, $orcamentoAux, 'nome');
        $descricao = $this->getValueRequest($request, $orcamentoAux, 'descricao');
        $categoria_id = $this->getValueRequest($request, $orcamentoAux, 'categoria_id');
        $condominio_id = $this->getValueRequest($request, $orcamentoAux, 'condominio_id');
        $urgente = $this->getValueRequest($request, $orcamentoAux, 'urgente');

        $chave = Formatacao::chave($nome . "" . $descricao . "" . $categoria_id . "" . $condominio_id . "" . $urgente);
        $orcamentoAux = Orcamento::where("chave", $chave)->first();

        if (true) {

            $this->newLog("Síndico cadastrando solicitação.");
            $condominio = Condominio::where("id", $request['condominio_id'])->first();
            if ($condominio) {
                $this->updateCondminioBairro($condominio);
            }

            DB::beginTransaction();
            try {


                $orcamento = new Orcamento();
                $orcamento->nome = $this->getValueRequest($request, $orcamento, 'nome');
                $orcamento->descricao = $this->getValueRequest($request, $orcamento, 'descricao');
                $orcamento->categoria_id = $this->getValueRequest($request, $orcamento, 'categoria_id');
                $orcamento->condominio_id = $this->getValueRequest($request, $orcamento, 'condominio_id');
                $orcamento->urgente = $this->getValueRequest($request, $orcamento, 'urgente');
                $orcamento->status = UtilStatusOrcamento::$ANALISANDO_CANDIDATOS;
                $orcamento->status_sindico = UtilStatusOrcamento::$ANALISANDO_CANDIDATOS;
                $orcamento->status_afiliado = UtilStatusOrcamento::$ANALISANDO_CANDIDATOS;
                $orcamento->modo = Util::getModusOperandi();
                $orcamento->formato_contrato_atual = 4;
                $orcamento->regiao_id = 12;
                $orcamento->chave = $chave;
                $validacao = OrcamentoBO::validarOrcamento($orcamento);

                if ($validacao->verifica()) {
                    //Verifica qual é a região atual do bairro onde o condomimío está localizado
                    $condominio = Condominio::where("id", $orcamento->condominio_id)->first();
                    $bairro_condominio = Bairro::where("id", $condominio->bairro_id)->first();
                    $regiao_id_bairro_condominio = $bairro_condominio->regiao_id;

                    if (!($regiao_id_bairro_condominio > 0)) {
                        $cidadesRegiao = RegiaoFaixaCep::where("cidade_id", $bairro_condominio->cidade_id)->first();
                        if ($cidadesRegiao) {
                            $regiao_id_bairro_condominio = $cidadesRegiao->regiao_id;
                        }
                    }

                    if (!($regiao_id_bairro_condominio > 0)) {
                        $orcamento->condominio->cep = str_replace("-", "", $orcamento->condominio->cep);
                        $cep = $orcamento->condominio->cep;
                        $cont = 0;
                        do {
                            $cont++;
                            $regiaoFaixaCep = RegiaoFaixaCep::where("cep", "LIKE", $cep)->orderBy("id", "desc")->first();
                            $cep = substr($cep, 0, strlen($orcamento->condominio->cep) - $cont);
                            if ($cep == "" || strlen($cep) <= 4 || $cont == 6) {
                                break;
                            }
                        } while (!$regiaoFaixaCep);

                        if ($regiaoFaixaCep) {
                            $regiao_id_bairro_condominio = $regiaoFaixaCep->regiao_id;
                        }
                    }

                    if (!($regiao_id_bairro_condominio > 0)) {
                        $regiao_id_bairro_condominio = 12; //Solicitações onde não foi encontrada uma região específica
                    }

                    $orcamento->regiao_id = $regiao_id_bairro_condominio;
                    $orcamento->save();

                    DB::commit();


                    SenderEmails::senderEnviarEmailAfiliados($orcamento);
                    $res = OrcamentoBO::transform($orcamento, $this->usuario_logado->tipo, $this->usuario_tipo_id);

                    return $this->successResponse('Solicitação #REF.: ' . $orcamento->id . ' criada com sucesso.', $res);
                } else {
                    DB::rollback();
                    return $this->errorResponse($validacao->getErros(), 403);
                }
            } catch (Exception $e) {
                DB::rollback();
                return $this->errorResponse($e->getMessage(), 403);
            }
        } else {
            $res = OrcamentoBO::transform($orcamentoAux, $this->usuario_logado->tipo, $this->usuario_tipo_id);
            return $this->successResponse('Solicitação #REF.: ' . $orcamentoAux->id . ' criada com sucesso.', $res);
        }
    }


    public function updateCondminioBairro($condominio)
    {
        $est = Estado::where("uf", "like", $condominio->estado, "or")->where("nome", "like", $condominio->estado, "or")->first();
        if (!$est) {
            return $this->errorResponse([array("error_code" => "invalid-uf", "error_message" => "Estado não encontrado")], 403);
        }

        $bairros = Bairro::where("chave", "LIKE", "%" . Formatacao::chave($condominio->bairro) . "%")->orderBy("id", "asc")->get();

        $encontrouBairro = false;
        foreach ($bairros as $bairroLinha) {
            $cid = Cidade::where("id", $bairroLinha->cidade_id)->first();
            $estado = Estado::where("id", $cid->estado_id)->first();
            if ((strtoupper($estado->uf) == strtoupper($condominio->estado) || Formatacao::chave($estado->nome) == Formatacao::chave($condominio->estado)) && Formatacao::chave($cid->nome) == Formatacao::chave($condominio->cidade)) {
                $condominio->estado = $estado->uf;
                $condominio->cidade = $cid->nome;
                $condominio->bairro = $bairroLinha->nome;
                $encontrouBairro = true;
                break;
            }
        }

        if ($encontrouBairro == false) {
            $cidadeReq = Cidade::where("chave", "LIKE", "%" . Formatacao::chave($condominio->cidade) . "%")->where("uf", "LIKE", $condominio->estado)->first();

            if (!$cidadeReq) {
                //return $this->errorResponse([array("error_code" => "invalid-cidade", "error_message" => "Não encontramos sua cidade. Fale com a administração.")], 403);

                $est = Estado::where("uf", "like", $condominio->estado, "or")->where("nome", "like", $condominio->estado, "or")->first();
                if (!$est) {
                    return $this->errorResponse([array("error_code" => "invalid-uf", "error_message" => "Estado não encontrado")], 403);
                }
                $cidadeReq = new Cidade();
                $cidadeReq->nome = $condominio->cidade;
                $cidadeReq->uf = $condominio->estado;
                $cidadeReq->estado_id = $est->id;
                $cidadeReq->save();
            }

            $bairro = new Bairro();
            $bairro->nome = $condominio->bairro;
            $bairro->cidade_id = $cidadeReq->id;
            $bairro->chave = Formatacao::chave($bairro->nome);
            $bairro->save();
            $condominio->bairro_id = $bairro->id;
        } else {
            $condominio->bairro_id = $bairroLinha->id;
        }

        $condominio->update();
    }






    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        $this->newLog("Visualizando solicitação.");
        try {
            if ($this->usuario_logado->tipo == "sindico") {
                $orcamento = Orcamento::findOrFail($id);
                if ($orcamento == null) {
                    return $this->errorResponse([Validacao::getError("Esta solicitação não existe mais.", "exists-solicitacao")], 403);
                }
                if ($orcamento->sindico_id != null && $orcamento->sindico_id != $this->usuario_tipo_id) {
                    return $this->errorResponse([Validacao::getError("Esta solicitação não existe mais.", "exists-solicitacao")], 403);
                }
            } elseif ($this->usuario_logado->tipo == "afiliado") {
                $orcamento = Orcamento::findOrFail($id);
                if ($orcamento == null) {
                    return $this->errorResponse([Validacao::getError("Esta solicitação não existe mais.", "exists-solicitacao")], 403);
                }
                if ($orcamento->afiliado_id != null && $orcamento->afiliado_id != $this->usuario_tipo_id) {
                    return $this->errorResponse([Validacao::getError("Esta solicitação não existe mais.", "exists-solicitacao")], 403);
                }
            } elseif ($this->usuario_logado->tipo == "vistoriador") {
                $orcamento = Orcamento::findOrFail($id);
            }

            $orcamento = OrcamentoBO::transform($orcamento, $this->usuario_logado->tipo, $this->usuario_tipo_id);
            if ($this->usuario_logado->tipo == "afiliado") {
                if ($orcamento['interesse'] == null || ($orcamento['interesse']->descartado_sindico <= 0 && $orcamento['interesse']->descartado_afiliado == 0)) {
                    return $this->successResponse('Success 1!', $orcamento);
                }
                return $this->successResponse('Success 2!', null);
            } else {
                return $this->successResponse('Success 3!', $orcamento);
            }
            return $this->successResponse('Success 4', $orcamento);
        } catch (Exception $e) {
            return $this->errorResponse([Validacao::getError("Esta solicitação não existe mais.", "invalid-exists-solicitacao")], 403);
        }
    }


    public function updateAssinaturaOrcamento($franqueado, $document_id)
    {
        if (isset($franqueado) && $franqueado->token_autentique) {
            $franqueado = Franqueado::where("id", $franqueado->id)->first();
            $dados = json_decode(DocumentosAutentique::listById($franqueado->token_autentique, $document_id));

            if (isset($dados->data)) {
                $doc = $dados->data->document->data;
            } else {
                $doc = null;
            }

            $document_id_autentique = isset($doc->id) ? $doc->id :  0;

            $orcamento = Orcamento::where("documento_id_autentique", $document_id_autentique)->first();

            if ($orcamento) {
                if ($orcamento->contrato_original == null)
                    $orcamento->contrato_original = isset($doc->files->original) ? $doc->files->original : null;

                if ($orcamento->contrato_assinado == null)
                    $orcamento->contrato_assinado = isset($doc->files->signed) ? $doc->files->signed : null;

                foreach ($doc->signatures as $j => $assinatura) {
                    $assinaturaLocal = OrcamentoAssinatura::where("public_id", $assinatura->public_id)->first();
                    if ($assinaturaLocal) {
                        $assinaturaLocal->signed = isset($assinatura->signed->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->signed->created_at)) : null;
                        $assinaturaLocal->viewed = isset($assinatura->viewed->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->viewed->created_at)) : null;
                        $assinaturaLocal->rejected = isset($assinatura->rejected->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->rejected->created_at)) : null;
                        $assinaturaLocal->update();
                    }
                }

                try {
                    $orcamento->update();
                } catch (Exception $e) {
                }
            }
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    // public function update(Request $request, $id)
    // {
    //     try {
    //         $orcamento = Orcamento::findOrFail($id);
    //         $orcamento->nome = $request['nome'];
    //         $orcamento->descricao = $request['descricao'];
    //         $orcamento->afilliado_id = $request['afilliado_id'];
    //         $orcamento->categoria_id = $request['categoria_id'];
    //         $orcamento->condominio_id = $request['condominio_id'];
    //         $orcamento->status_sindico = $request['status_sindico'];
    //         $orcamento->status_afiliado = $request['status_afiliado'];
    //         $orcamento->status = $request['status'];
    //         $orcamento->update();
    //         $data = new ResourcesOrcamento($orcamento);
    //         return $this->successResponse('Orcamento updated!', $data);
    //     } catch (Exception $e) {
    //         return $this->errorResponse('Error processing your request');
    //     }
    // }

    public function partial_update(Request $request, $id)
    {

        try {
            $orcamento = Orcamento::findOrFail($id);
            //$orcamento->afiliado_id = $this->getValueRequest($request, $orcamento, 'afiliado_id');
            $orcamento->{$request['column_name']} = $request['status'];
            if ($request['status'] && $request['column_name'] == "status" || $this->usuario_logado->tipo == "sindico") {
                $orcamento->status_sindico = $request['status'];
                $orcamento->status_afiliado = $request['status'];

                if ($request['status'] == 8 || $request['status'] == 9) {
                    $orcamento->status = $request['status'];
                }
                $this->newLog("Alteração de status geral de orçamento");
            }

            if (isset($request['motivo_cancelamento'])) {
                $orcamento->motivo_cancelamento = $request['motivo_cancelamento'];
            }
            $orcamento->update();

            $orcamento = OrcamentoBO::transform($orcamento, $this->usuario_logado->tipo, $this->usuario_tipo_id);
            SenderEmails::senderEnviarEmailOrcamentoUpdate($orcamento, $this->usuario_logado->tipo);

            if ($this->usuario_logado->tipo == "afiliado") {
                if (isset($orcamento['interesse'])) {
                    if ($orcamento['interesse'] == null) {
                        return $this->successResponse('Orcamento updated!', $orcamento);
                    } elseif (($orcamento['interesse'] != null && ($orcamento['interesse']->descartado_sindico == 0 || $orcamento['interesse']->descartado_sindico == -1)) && $orcamento['interesse']->descartado_afiliado == 0) {
                        return $this->successResponse('Orcamento updated!', $orcamento);
                    }
                }
                return $this->successResponse('Orcamento updated!', null);
            } else {
                return $this->successResponse('Orcamento updated!', $orcamento);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function escolha_afiliado_orcamento(Request $request, $id)
    {
        $this->newLog("Síndico escolhendo um afiliado.");
        try {

            $orcamento = Orcamento::findOrFail($id);
            $afiliado = Afiliado::where("id", $request['afiliado_id'])->first();
            if ($afiliado) {
                $usuarioApp = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
            }

            if ($afiliado == null || $usuarioApp == null) {
                return $this->errorResponse([Validacao::getError("Esta empresa não está mais em no quadro. Por favor, escolha outro.", "candidato-inexistente")], 403);
            }


            $orcamento->afiliado_id = $this->getValueRequest($request, $orcamento, 'afiliado_id');
            $orcamento->status = UtilStatusOrcamento::$AGUARDANDO_CONTRATO;
            $orcamento->status_afiliado = UtilStatusOrcamento::$AGUARDANDO_CONTRATO;
            $orcamento->status_sindico = UtilStatusOrcamento::$AGUARDANDO_CONTRATO;
            $orcamento->update();

            $afiliado = Afiliado::where("id", $orcamento->afiliado_id)->first();
            $sindico = $orcamento->condominio()->withTrashed()->first()->sindico()->withTrashed()->first();

            Notificacao::painelNotificarAfiliadoEscolhido($orcamento, $afiliado);
            Notificacao::painelNotificarSindicioDeAfiliadoEscolhido($orcamento, $afiliado, $sindico);

            //Enviar mensagens de recusa para os demais afialiados
            $afiliadosRecusados  = AfiliadoOrcamentoInteresse::where("orcamento_id", $orcamento->id)->where("afiliado_id", "<>", $orcamento->afiliado_id)->where("descartado_afiliado", 0)->whereIn("descartado_sindico", [-1, 0])->get();

            foreach ($afiliadosRecusados as $afiliadoRecusado) {
                if ($afiliadoRecusado->afiliado) {
                    Notificacao::painelNotificarAfiliadoNaoEscolhido($orcamento, $afiliadoRecusado->afiliado);
                    $usuarioApp = UsuarioApp::where("id", $afiliadoRecusado->afiliado->usuario_app_id)->first();

                    if ($usuarioApp) {
                        if ($usuarioApp->token_notification) {
                            SenderNotificacao::recusarOrcamento($orcamento->id . " - " . $orcamento->nome, $usuarioApp->token_notification, $orcamento);
                        }
                        SenderEmails::enviarEmailRecusarOrcamento($usuarioApp->email, $afiliadoRecusado->afiliado->razao_social, "", $orcamento->id . " - " . $orcamento->nome);
                    }
                }
            }



            $orcamento = OrcamentoBO::transform($orcamento, $this->usuario_logado->tipo, $this->usuario_tipo_id);
            $afiliado = Afiliado::where("id", $orcamento->afiliado_id)->first();

            if ($afiliado) {
                $this->enviarEmailAfiliadoContratado($afiliado->email, $id);
                $usuarioApp = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
                if ($usuarioApp && $usuarioApp->email != $afiliado->email) {
                    $this->enviarEmailAfiliadoContratado($usuarioApp->email, $id);
                }
                if ($usuarioApp && $usuarioApp->token_notification) {
                    $this->enviarNotificacaoAfiliadoContratado($usuarioApp->token_notification, $id);
                }
            }

            $regiao = FranqueadoRegiao::where("regiao_id", $orcamento->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
            if ($regiao) {
                $franqueado = Franqueado::where("id", $regiao->franqueado_id)->first();
                if ($franqueado) {
                    $this->enviarEmailFranquedoContratado($franqueado->email, $id);
                }
            }
            $orcamento->afiliado = $afiliado;

            $this->newLog("Síndico escolheu um afiliado com sucesso.");
            return $this->successResponse('Orcamento updated!', $orcamento);
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }

    public function enviarNotificacaoAfiliadoContratado($email, $id)
    {
        SenderNotificacao::enviarNotificacaoAfiliadoContratado($email, $id);
    }

    public function enviarEmailAfiliadoContratado($email, $id)
    {

        $this->newLog("Envio de e-mail para afiliado");


        $config = Configuracao::orderBy("id", "DESC")->first();

        $link_android = "";
        $link_ios = "";

        if ($config['link_android']) {
            $link_android = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config['link_android']}'>
                                <img src='https://casadosindico.srv.br/assets/playstore.png' />
                             </a>";
        }

        if ($config['link_ios']) {
            $link_ios = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config['link_ios']}'>
                            <img src='https://casadosindico.srv.br/assets/appstore.png' />
                            </a>";
        }

        $aux = $email;
        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td>CASA DO SÍNDICO</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img style='' src='https://casadosindico.srv.br/assets/images/casa_logo.png'>
                                    <h1>Você acaba de ser escolhido por um síndico para realizar o serviço de solicitação #$id. Agora, aguarde o contrato.</h1>
                                    <h2 style='text-align: center;'>Acesse o aplicativo Casa do Síndico com o e-mail $aux e confira.</h2>
                                </div>
                                <p style='text-align: center;'>
                                    <h3>Baixe o aplicativo em uma das lojas</h3>
                                    $link_android &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    $link_ios
                                    <p>
                                        Se você não lembrar da sua senha, baixe o aplicativo, clique em <b>Já possuo uma conta</b> e depois clique em <b>Esqueci minha senha</b>. Você receberá uma senha nova neste mesmo e-mail.
                                    </p>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>Casa do Síndico.</b><br>
                                " . $config['endereco'] . "
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            $sender = new EnviarEmail();
            $res = $sender->send(
                "Casa do Síndico - Nova solicitação pelo App",
                $html,
                $email,
                $email
            );
            return $this->successResponse('E-mail enviado com sucesso!', $res);
        } catch (Exception $e) {
            return $this->successResponse('Erro!', $e);
        }
    }

    public function enviarEmailFranquedoContratado($email, $id)
    {

        $this->newLog("Envio de e-mail para franqueado");
        $config = Configuracao::orderBy("id", "DESC")->first();

        $link_android = "";
        $link_ios = "";

        if ($config['link_android']) {
            $link_android = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config['link_android']}'>
                                <img src='https://casadosindico.srv.br/assets/playstore.png' />
                             </a>";
        }

        if ($config['link_ios']) {
            $link_ios = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config['link_ios']}'>
                            <img src='https://casadosindico.srv.br/assets/appstore.png' />
                            </a>";
        }

        $aux = $email;
        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td>CASA DO SÍNDICO</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img style='' src='https://casadosindico.srv.br/assets/images/casa_logo.png'>
                                    <h1>Um afiliado acaba de ser escolhido por um síndico para realizar o serviço de solicitação #$id</h1>
                                    <h2 style='text-align: center;'>Acesse o aplicativo Casa do Síndico com o e-mail $aux e confira.</h2>
                                </div>
                                <p style='text-align: center;'>
                                    <h3>Baixe o aplicativo em uma das lojas</h3>
                                    $link_android &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    $link_ios
                                    <p>
                                        Se você não lembrar da sua senha, baixe o aplicativo, clique em <b>Já possuo uma conta</b> e depois clique em <b>Esqueci minha senha</b>. Você receberá uma senha nova neste mesmo e-mail.
                                    </p>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>Casa do Síndico.</b><br>
                                " . $config['endereco'] . "
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            $sender = new EnviarEmail();
            $res = $sender->send(
                "Casa do Síndico - Nova solicitação pelo App",
                $html,
                $email,
                $email
            );
            return $this->successResponse('E-mail enviado com sucesso!', $res);
        } catch (Exception $e) {
            return $this->successResponse('Erro!', $e);
        }
    }




    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Orcamento  $orcamento
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        $this->newLog("Deletando orçamento");
        try {
            $orcamento = Orcamento::findOrFail($id);
            $orcamento->delete();
            $data = new ResourcesOrcamento($orcamento);
            return $this->successResponse('Orcamento deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function status($status)
    {
        $this->newLog("Listando orçamento por status");
        try {
            $orcamentos = Orcamento::where('status', $status)->get();
            $data = new ResourcesOrcamento($orcamentos);
            return $this->successResponse('Success!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function statusAfiliado($status)
    {
        $this->newLog("Listando orçamento por status_afiliado");
        try {
            $orcamentos = Orcamento::where('status_afiliado', $status)->get();
            $data = new ResourcesOrcamento($orcamentos);
            return $this->successResponse('Success!', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse('Error processing your request');
        }
    }

    public function statusSindico($status)
    {
        $this->newLog("Listando orçamento por status_sindico");
        try {
            $orcamentos = Orcamento::where('status_sindico', $status)->get();
            $data = new ResourcesOrcamento($orcamentos);
            return $this->successResponse('Success!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }


    public function enviar_valor_orcamento(Request $request)
    {
        $this->newLog("Envio do valor do orçamento");
        $orcamento = (object) $request['orcamento'];
        $valor_orcamento = $request['valor_orcamento'];
        if ($valor_orcamento)
            $valor_orcamento = str_replace(",", ".", str_replace(".", "", $valor_orcamento));;
        try {

            $orcamentoAfiliadoInteresse = AfiliadoOrcamentoInteresse::where("orcamento_id", $orcamento->id)->where("afiliado_id", $this->usuario_tipo_id)->orderBy("id", "desc")->first();
            $orcamentoAfiliadoInteresse->valor_orcamento = $valor_orcamento;
            $orcamentoAfiliadoInteresse->update();

            $condominio = Condominio::where("id", $request['orcamento']->condominio_id)->first();
            $sindico = Sindico::where("id", $condominio->sindico_id)->first();
            $usuarioApp = UsuarioApp::where("id", $sindico->usuario_app_id)->first();
            if ($usuarioApp && $usuarioApp->token_notification) {
                $afiliado = Afiliado::where("id", $this->usuario_tipo_id)->first();
                SenderNotificacao::enviarPreencheuValorOrcamento($usuarioApp->token_notification, $orcamento->id, $afiliado->razao_social);
            }

            return $this->successResponse('Valor enviado com sucesso', $orcamentoAfiliadoInteresse);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function nova_avaliacao(Request $request)
    {
        $this->newLog("Avaliando serviço");
        try {
            $orcamento = Orcamento::where("id", $request['orcamento_id'])->first();
            if ($orcamento && $orcamento->condominio->sindico->id == $this->usuario_tipo_id) {
                $orcamento->avaliacao = $request['nota'];
                $orcamento->update();

                return $this->successResponse('Avaliação criada com sucesso', $orcamento);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function nova_vistoria(Request $request)
    {
        $this->newLog("Solicitação de nova vistoria");
        $orcamento_id = $request['orcamento_id'];
        $descricao = $request['descricao'];
        try {
            $vistoria = new Vistoria();
            $vistoria->orcamento_id = $orcamento_id;
            $vistoria->descricao = $descricao;
            $vistoria->forma_cadastro = "Via Síndico";
            $vistoria->save();


            $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();

            if ($orcamento) {
                $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $orcamento->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
                if ($franqueadoRegiao) {
                    $vistoriadores = Vistoriador::where("franqueado_id", "=", $franqueadoRegiao->franqueado_id, "or")->where("franqueado_id", "=", null, "or")->get();
                    foreach ($vistoriadores as $vistoriador) {
                        if ($vistoriador) {
                            $usuarioApp = UsuarioApp::where("id", $vistoriador->usuario_app_id)->first();
                            if ($usuarioApp) {
                                if (isset($usuarioApp->token_notification)) {
                                    SenderNotificacao::novaVistoria($usuarioApp->token_notification, $vistoriador);
                                }
                            }
                        }
                    }

                    Notificacao::painelNotificarSindicioNovaVistoria($orcamento, $orcamento->condominio()->withTrashed()->first()->sindico()->withTrashed()->first());
                    $orcamento->data_atualizacao = Carbon::now();
                    $orcamento->update();
                }
            }

            return $this->successResponse('Vistoria solicitada com sucesso', $vistoria);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }
}
