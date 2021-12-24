<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\AfiliadoCategoria;
use App\Http\Resources\Orcamento as ResourcesOrcamento;
use App\Models\Afiliado;
use App\Models\AfiliadoCategorium;
use App\Models\AfiliadoFranqueadoAsaas;
use App\Models\AfiliadoOrcamentoInteresse;
use App\Models\AfiliadoRegiao;
use App\Models\BO\OrcamentoBO;
use App\Models\Categoria;
use App\Models\Condominio;
use App\Models\ContratoAssinatura;
use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\ImagemOrcamento;
use App\Models\Orcamento;
use App\Models\OrcamentoAssinatura;
use App\Models\PlanoAssinaturaAfiliadoRegiao;
use App\Models\Sindico;
use App\Models\UsuarioApp;
use App\Models\Vistoria;
use App\Models\Vistoriador;
use App\Models\VistoriaImagem;
use App\Util\Autentique\DocumentosAutentique;
use App\Util\StatusPlano;
use App\Util\Formatacao;
use App\Util\StatusAsass;
use App\Util\StatusAssinaturaPlano;
use App\Util\StatusOrcamento as UtilStatusOrcamento;
use App\Util\StatusVistoria;
use App\Util\Util;
use App\Util\Validacao;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use StatusOrcamento;

class OrcamentoControllerAfiliado extends Controller
{


    public function __construct(Request $request)
    {
        parent::__construct($request, new Afiliado());
    }

    public function novaAssinaturaPlanoAsaas($franqueado, $planoAssintura)
    {
        if ($planoAssintura->valor >= 10 && $planoAssintura->gerenciado_plano_assas_franquia == 0 && $planoAssintura->asaas_assinatura_id == null && $planoAssintura->status_afiliado == 1 && ($planoAssintura->statusPlano == StatusPlano::$PENDENTE || $planoAssintura->statusPlano == StatusPlano::$ATIVO)) {
            $afiliadoAsaas = AfiliadoFranqueadoAsaas::where("modo", Util::getModusOperandi())->where("afiliado_id", $this->usuario_tipo_id)->where("franqueado_id", $franqueado->id)->orderBy("id", "desc")->first();

            $afiliado = Afiliado::where("id", $this->usuario_tipo_id)->first();

            if ($franqueado) {
                if ($afiliadoAsaas) {
                    $planoAssintura->asaas_customer_id = $afiliadoAsaas->asaas_customer_id;
                    $res = $this->criarAssinatura($planoAssintura, Util::getTokenAsaasFranqueadoById($franqueado->id));

                    if (isset($res['errors'][0]["code"])) {
                        if ($res['errors'][0]["code"] == "invalid_customer") {
                            $res = Asaas::novoCustomer($afiliado, Util::getTokenAsaasFranqueadoById($franqueado->id), true, $franqueado->id);
                            $planoAssintura->asaas_customer_id = isset($res["id"]) ? $res["id"] : null;
                            $res = Asaas::criarAssinatura($planoAssintura,  Util::getTokenAsaasFranqueadoById($franqueado->id));
                        }
                    }

                    $planoAssintura->asaas_assinatura_id = isset($res["id"]) ? $res["id"] : null;
                    $planoAssintura->data_expiracao = isset($res["nextDueDate"]) ? $res["nextDueDate"] : null;
                    $planoAssintura->statusPlano = isset($res["id"]) ? StatusPlano::$ATIVO : StatusPlano::$PENDENTE;

                    $planoAssintura->update();
                    SenderEmails::enviarEmailNovaAssinatura($franqueado->email, $franqueado->nome, $afiliado->razao_social);
                    DB::commit();
                    return $planoAssintura;
                } else {
                    $resNewCustomer = Asaas::novoCustomer($afiliado, Util::getTokenAsaasFranqueadoById($franqueado->id), true, $franqueado->id);
                    $afiliadoAsaas = AfiliadoFranqueadoAsaas::where("modo", Util::getModusOperandi())->where("afiliado_id", $this->usuario_tipo_id)->where("franqueado_id", $franqueado->id)->orderBy("id", "desc")->first();
                    $planoAssintura->asaas_customer_id = isset($resNewCustomer["id"]) ? $resNewCustomer["id"] : null;
                    $res = $this->criarAssinatura($planoAssintura, Util::getTokenAsaasFranqueadoById($franqueado->id));
                    $planoAssintura->asaas_assinatura_id = isset($res["id"]) ? $res["id"] : null;

                    $planoAssintura->data_expiracao = isset($res["nextDueDate"]) ? $res["nextDueDate"] : null;
                    $planoAssintura->statusPlano = isset($res["id"]) ? StatusPlano::$ATIVO : StatusPlano::$PENDENTE;
                    $planoAssintura->update();
                    SenderEmails::enviarEmailNovaAssinatura($franqueado->email, $franqueado->nome, $afiliado->razao_social);
                    DB::commit();
                    return $planoAssintura;
                }
            }
        }

        return null;
    }

    private function criarAssinatura($assinatura, $token)
    {
        return Asaas::criarAssinatura($assinatura, $token);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * 
     */
    public function index()
    {
        set_time_limit(0);
        $this->newLog("Afiliado listando solicitações.");

        try {

            $contratos = AfiliadoRegiao::where("afiliado_id", $this->usuario_tipo_id)->orderBy("id", "desc")->where("modo", Util::getModusOperandi())->get();
            foreach ($contratos as $i => $contrato) {
                $contratos[$i]['regiao'] = $contrato->regiao()->withTrashed()->first();
                $contratos[$i]['plano_assinatura'] = PlanoAssinaturaAfiliadoRegiao::withTrashed()->where("id", $contrato->plano_assinatura_afiliado_regiao_id)->first();
                if (isset($contratos[$i]['plano_assinatura']->data_cancelamento) &&  $contratos[$i]['plano_assinatura']->data_cancelamento != null) {
                    $planoAssinatura = PlanoAssinaturaAfiliadoRegiao::withTrashed()->where("id", $contratos[$i]['plano_assinatura']->id)->first();
                    if ($planoAssinatura) {
                        $planoAssinatura->statusPlano = StatusPlano::$CANCELADO;
                        $planoAssinatura->data_expiracao = null;
                        $planoAssinatura->update();
                    }

                    $contratos[$i]['plano_assinatura'] = PlanoAssinaturaAfiliadoRegiao::withTrashed()->where("id", $contrato->plano_assinatura_afiliado_regiao_id)->first();
                } else if (isset($contratos[$i]['plano_assinatura']->id)) {
                    $regiaoAfiliado = AfiliadoRegiao::where("plano_assinatura_afiliado_regiao_id", $contratos[$i]['plano_assinatura']->id)->first();
                    $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $regiaoAfiliado->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
                    $franqueado = Franqueado::where("id", $franqueadoRegiao->franqueado_id)->first();
                    $this->novaAssinaturaPlanoAsaas($franqueado, $contratos[$i]['plano_assinatura']);
                }
            }


            $orcamentosAux = [];
            if ($this->usuario_logado->tipo == "afiliado") {


                //$orcamentos_aux1 = Orcamento::where("afiliado_id", $this->usuario_tipo_id)->orderBy("id", "ASC")->get();

                $orcamentos_aux11 = Orcamento::where("afiliado_id", "=", null)->whereIn("status", [UtilStatusOrcamento::$ANALISANDO_CANDIDATOS, UtilStatusOrcamento::$ANALISANDO_ORCAMENTOS, UtilStatusOrcamento::$CANCELADO_PELO_ADMIN, UtilStatusOrcamento::$CANCELADO_PELO_AFILIADO, UtilStatusOrcamento::$CANCELADO_PELO_SINDICO])->orderBy("id", "ASC")->get();
                $orcamentos_aux22 = Orcamento::where("afiliado_id", "=", $this->usuario_tipo_id)->whereNotIn("status", [UtilStatusOrcamento::$ANALISANDO_CANDIDATOS, UtilStatusOrcamento::$ANALISANDO_ORCAMENTOS])->orderBy("id", "ASC")->get();

                $orcamentos_aux = [];
                foreach ($orcamentos_aux11 as $v) {
                    $orcamentos_aux[] = $v;
                }
                foreach ($orcamentos_aux22 as $v) {
                    $orcamentos_aux[] = $v;
                }

                // foreach ($orcamentos_aux1 as $valor) {
                //     $orcamentos_aux[] = $valor;
                // }

                $teste = [];
                // $orcamentos_aux = [];
                // foreach ($orcamentos_aux2 as $valor) {
                //     if ($valor['afiliado_id'] == 0  || $valor['afiliado_id'] == null || $valor['afiliado_id'] == $this->usuario_tipo_id) {
                //         $orcamentos_aux[] = $valor;
                //     } else {
                //         //$teste[] = $valor;
                //     }
                // }
                // /// $orcamentos_aux = array_merge($orcamentos_aux1, $orcamentos_aux2);

                $categoriasAfiliado = AfiliadoCategorium::where("afiliado_id", $this->usuario_tipo_id)->where("status", "aprovado")->get();
                $regioesAfiliado = AfiliadoRegiao::where("afiliado_id", $this->usuario_tipo_id)->where("modo", Util::getModusOperandi())->orderBy("id", "desc")->get();

                $addSolicitacao = false;
                $inadimplenciaFranquia = [];
                foreach ($orcamentos_aux as $orc) {
                    $orc = OrcamentoBO::transformList($orc, $this->usuario_logado->tipo, $this->usuario_tipo_id);

                    $orc['assinatura'] = OrcamentoAssinatura::where("afiliado_id", $this->usuario_tipo_id)->where("orcamento_id", $orc['id'])->orderBy("id", "desc")->first();


                    foreach ($categoriasAfiliado as $catAfil) {
                        $teste[] = $orc;

                        if ($catAfil->categoria_id == $orc->categoria_id) {

                            foreach ($regioesAfiliado as $regAfil) {
                                if (isset($regAfil->plano_assinatura_afiliado_regiao_id)) {

                                    $planoRegiao = PlanoAssinaturaAfiliadoRegiao::where("id", $regAfil->plano_assinatura_afiliado_regiao_id)->first();

                                    if ($planoRegiao) {
                                        if ($planoRegiao->gerenciado_plano_assas_franquia === null && $planoRegiao->asaas_assinatura_id == null) {
                                            $planoRegiao->gerenciado_plano_assas_franquia = 1;
                                            $planoRegiao->save();
                                        } else if ($planoRegiao->gerenciado_plano_assas_franquia === null && $planoRegiao->asaas_assinatura_id != null) {
                                            $planoRegiao->gerenciado_plano_assas_franquia = 0;
                                            $planoRegiao->save();
                                        }
                                    }



                                    if ($planoRegiao) {


                                        $autorizeAsaas = false;
                                        $autorizeAutentique = false;

                                        if ($orc->afiliado_id == $this->usuario_tipo_id) {
                                            $autorizeAsaas = true;
                                            $autorizeAutentique = true;
                                        } else if ($orc->regiao_id == $regAfil->regiao_id) {
                                            //Plano subscription asaas geerenciado pela franquia
                                            if ($planoRegiao->gerenciado_plano_assas_franquia == 1 && $planoRegiao->statusPlano == StatusPlano::$ATIVO) {
                                                $autorizeAsaas = true;
                                            } else if ($planoRegiao->gerenciado_plano_assas_franquia == 0 && ($planoRegiao->statusPlano == StatusPlano::$ATIVO || $planoRegiao->statusPlano == StatusPlano::$INADIMPLENTE || $planoRegiao->statusPlano == StatusPlano::$EM_PROCESSO_CANCELAMENTO) && $planoRegiao->asaas_assinatura_id != null && $planoRegiao->data_expiracao != null) {
                                                $diasAtrasado = Formatacao::diasPeriodo(date("Y-m-d"), $planoRegiao->data_expiracao);
                                                if ($diasAtrasado >= -10) {
                                                    $autorizeAsaas = true;
                                                }
                                            }

                                            // if ($planoRegiao->statusPlano != StatusPlano::$CANCELADO) {
                                            //     $autorizeAsaas = true;
                                            // }


                                            if ($planoRegiao->tipo_assinatura == 1 && $planoRegiao->status_afiliado == 1) {
                                                //Altenticado pelo autentique
                                                $autorizeAutentique = true;
                                            } else if ($planoRegiao->tipo_assinatura == 2) {
                                                //Autenticado pela franquia
                                                $autorizeAutentique = true;
                                            }
                                        }

                                        //Veriifcar indimplencia
                                        $isInadimplente = true;
                                        $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $orc->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
                                        if ($franqueadoRegiao) {
                                            $franqueado = Franqueado::where("id", $franqueadoRegiao->franqueado_id)->first();
                                            if ($franqueado) {
                                                $afiliadoFranqueadoAsaas = AfiliadoFranqueadoAsaas::where("afiliado_id", $this->usuario_tipo_id)->where("franqueado_id", $franqueado->id)->orderBy("id", "desc")->first();
                                                if ($afiliadoFranqueadoAsaas) {
                                                    $vencidas =  $afiliadoFranqueadoAsaas->asaas_cobrancas_vencidas ? json_decode($afiliadoFranqueadoAsaas->asaas_cobrancas_vencidas) : [];
                                                    $isInadimplente = Asaas::isPossuiCobrancaVencida($vencidas);
                                                } elseif ($planoRegiao->gerenciado_plano_assas_franquia == 1 && $planoRegiao->statusPlano == StatusPlano::$ATIVO) {
                                                    $isInadimplente = false;
                                                }
                                            }
                                        }


                                        $afiliado = Afiliado::where("id", $this->usuario_tipo_id)->first();
                                        $usuarioApp = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
                                        //Verifica se está autorizado a ver este orçamento
                                        if ($orc->afiliado_id == $this->usuario_tipo_id || ($autorizeAsaas && $autorizeAutentique && $usuarioApp && $usuarioApp->data_confirmacao && $isInadimplente == false)) {
                                            $orcamentosAux[$orc->id] = $orc;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }


            $orcamentos = [];

            foreach ($orcamentosAux as $o) {
                if ($this->usuario_logado->tipo == "afiliado") {
                    if (($o['interesse'] == null && $o['status'] == 1) || $this->usuario_tipo_id == $o->afiliado_id) {
                        if ($o->modo == Util::getModusOperandi()) {
                            $orcamentos[] = $o;
                        }
                    } else if ($o['interesse'] != null && $o['interesse']->descartado_sindico != 1 && $o['interesse']->descartado_afiliado == 0) {
                        if ($o->modo == Util::getModusOperandi()) {
                            $orcamentos[] = $o;
                        }
                    }
                }
            }


            return $this->successResponse("ok", $orcamentos);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }



    function updateAssinaturaPlanoById($id)
    {
        $this->newLog("Update assinaturas.");
        $planoAssinatura = PlanoAssinaturaAfiliadoRegiao::where("id", $id)->first();
        $regiaoAfiliado = AfiliadoRegiao::where("plano_assinatura_afiliado_regiao_id", $planoAssinatura->id)->first();
        $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $regiaoAfiliado->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
        $d = null;
        if ($franqueadoRegiao && $planoAssinatura) {
            $d = $this->updateAssinaturas($planoAssinatura, $franqueadoRegiao->franqueado_id);
            $d = $this->updateAssinaturasAsaas($planoAssinatura, $franqueadoRegiao->franqueado_id);
        }
        return  $this->successResponse('Success', $d);
    }

    function updateAssinaturasAsaas($assinatura, $franqueado_id)
    {
        $tokenAsaas = Util::getTokenAsaasFranqueadoById($franqueado_id);
        $nova_data = null;
        if ($assinatura->asaas_assinatura_id && $tokenAsaas && $assinatura->gerenciado_plano_assas_franquia == 0) {
            $assinatura_asaas = Asaas::getAssinaturaById($assinatura->asaas_assinatura_id, $tokenAsaas);
            if ($assinatura_asaas) {
                $assinatura->data_expiracao = $assinatura_asaas['nextDueDate'];
                if ($assinatura_asaas['status'] == "ACTIVE" && $assinatura['data_cancelamento'] == null) {
                    $statusPlano = StatusPlano::$ATIVO;
                } else if ($assinatura_asaas['status'] == "EXPIRED" && $assinatura['data_cancelamento'] == null) {
                    $statusPlano = StatusPlano::$INADIMPLENTE;
                } else if ($assinatura['data_cancelamento'] != null) {
                    $statusPlano = StatusPlano::$CANCELADO;
                } else {
                    $statusPlano = $assinatura_asaas['status'];
                }

                $assinatura->statusPlano = $statusPlano;
                $assinatura->update();
            } else {
                if ($assinatura['data_cancelamento'] == null) {
                    $assinatura->data_cancelamento = Carbon::now();
                }

                $assinatura->statusPlano = StatusPlano::$CANCELADO;
                $assinatura->update();
            }
        }
        return $assinatura;
    }

    function updateAssinaturas($planoAssinatura, $franqueado_id)
    {
        $tokenAutentique = Util::getTokenAutentique($franqueado_id);
        $tokenAsaas = Util::getTokenAsaasFranqueadoById($franqueado_id);

        if ($tokenAutentique && $planoAssinatura->documento_id_autentique  && $planoAssinatura->status != StatusAssinaturaPlano::$ASSINADO  && $planoAssinatura->status != StatusAssinaturaPlano::$RESUSADO) {

            $res = DocumentosAutentique::listById($tokenAutentique, $planoAssinatura->documento_id_autentique);

            if ($res && !isset($res->errors) && isset($res->data)) {
                $doc = $res->data->document;
                if ($planoAssinatura->arquivo_original_autentique == null)
                    $planoAssinatura->arquivo_original_autentique = isset($doc->files->original) ? $doc->files->original : null;

                if ($planoAssinatura->arquivo_assinado == null)
                    $planoAssinatura->arquivo_assinado = isset($doc->files->signed) ? $doc->files->signed : null;

                if (isset($doc->signatures))
                    foreach ($doc->signatures as $j => $assinatura) {
                        $assinaturaLocal = ContratoAssinatura::where("public_id", $assinatura->public_id)->where("plano_assinatura_afiliado_regiao_id", $planoAssinatura->id)->where("tipo_usuario", "afiliado")->first();
                        if ($assinaturaLocal) {
                            $assinaturaLocal->signed = isset($assinatura->signed->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->signed->created_at)) : null;
                            $assinaturaLocal->viewed = isset($assinatura->viewed->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->viewed->created_at)) : null;
                            $assinaturaLocal->rejected = isset($assinatura->rejected->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->rejected->created_at)) : null;
                            $assinaturaLocal->update();
                        }
                    }

                $assinaturasLocal = ContratoAssinatura::where("plano_assinatura_afiliado_regiao_id", $planoAssinatura->id)->get();

                $assinadoFranqueado = false;
                $assinadoTestemunha1 = false;
                $assinadoTestemunha2 = false;
                $assinadoAfiliado = false;

                foreach ($assinaturasLocal as $assLocal) {
                    if ($assLocal->tipo_usuario == "franqueado" && $assLocal->signed) {
                        $assinadoFranqueado = true;
                    } else if ($assLocal->tipo_usuario == "testemunha1" && $assLocal->signed) {
                        $assinadoTestemunha1 = true;
                        $planoAssinatura->status_testemunha1 = StatusAssinaturaPlano::$ASSINADO;
                    } else if ($assLocal->tipo_usuario == "afiliado" && $assLocal->signed) {
                        $assinadoAfiliado = true;
                        $planoAssinatura->status_afiliado = StatusAssinaturaPlano::$ASSINADO;
                    } else if ($assLocal->tipo_usuario == "testemunha2" && $assLocal->signed) {
                        $assinadoTestemunha2 = true;
                        $planoAssinatura->status_testemunha2 = StatusAssinaturaPlano::$ASSINADO;
                    }

                    if ($assLocal->tipo_usuario == "franqueado") {
                        $assinaturas[$planoAssinatura->id]["franqueado"] = $assLocal;
                    } else if ($assLocal->tipo_usuario == "testemunha1") {
                        $assinaturas[$planoAssinatura->id]["testemunha1"] = $assLocal;
                    } else if ($assLocal->tipo_usuario == "afiliado") {
                        $assinaturas[$planoAssinatura->id]["afiliado"] = $assLocal;
                    } else if ($assLocal->tipo_usuario == "testemunha2") {
                        $assinaturas[$planoAssinatura->id]["testemunha2"] = $assLocal;
                    }
                }

                if ($assinadoTestemunha1 && $assinadoAfiliado && $assinadoTestemunha2) {
                    $planoAssinatura->status = StatusAssinaturaPlano::$ASSINADO;
                }

                try {
                    $planoAssinatura->update();
                } catch (Exception $e) {
                    return $e;
                }
            }
        } else {
            return null;
        }
    }
}
