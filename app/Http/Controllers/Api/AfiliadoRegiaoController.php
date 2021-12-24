<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\AfiliadoRegiao as ResourcesAfiliadoRegiao;
use App\Http\Resources\FranqueadoRegiaoPlanoDisponibilizado as ResourcesFranqueadoRegiaoPlanoDisponibilizado;
use App\Models\Afiliado;
use App\Models\AfiliadoFranqueadoAsaas;
use App\Models\AfiliadoRegiao;
use App\Models\ContratoAssinatura;
use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\FranqueadoRegiaoPlanoDisponibilizado;
use App\Models\Notificacao;
use App\Models\Orcamento;
use App\Models\OrcamentoAssinatura;
use App\Models\PlanoAssinaturaAfiliadoRegiao;
use App\Models\PlanoDisponivelFranqueado;
use App\Util\Autentique\DocumentosAutentique;
use App\Util\StatusAssinaturaPlano;
use App\Util\StatusOrcamento;
use App\Util\StatusPlano;
use App\Util\Util;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class AfiliadoRegiaoController extends Controller
{
    public function __construct(Request $request)
    {

        parent::__construct($request, new AfiliadoRegiao(), new Afiliado());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->newLog("Afiliado listando suas regiões");
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
                    $this->updateAssinaturaPlanoByIdInternal($contratos[$i]['plano_assinatura']->id);
                }
                $contratos[$i]['assinatura'] = ContratoAssinatura::withTrashed()->where("afiliado_id", $this->usuario_tipo_id)->where("plano_assinatura_afiliado_regiao_id", $contrato->plano_assinatura_afiliado_regiao_id)->orderBy("id", "desc")->first();
            }
            ///$afiliado_regioes = $afiliado->regiaos()->get();
            return $this->successResponse('Success', $contratos);
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    function updateAssinaturaPlanoById($id)
    {
        $this->newLog("Atualizando assinaturas");
        $d = null;
        $planoAssinatura = PlanoAssinaturaAfiliadoRegiao::where("id", $id)->first();
        if ($planoAssinatura) {
            $regiaoAfiliado = AfiliadoRegiao::where("plano_assinatura_afiliado_regiao_id", $planoAssinatura->id)->first();
            if ($regiaoAfiliado) {
                $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $regiaoAfiliado->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
                if ($franqueadoRegiao) {
                    $d = $this->updateAssinaturas($planoAssinatura, $franqueadoRegiao->franqueado_id);
                    $d = $this->updateAssinaturasAsaas($planoAssinatura, $franqueadoRegiao->franqueado_id);
                    $franqueado = Franqueado::where("id", $franqueadoRegiao->franqueado_id)->first();
                    $this->novaAssinaturaPlanoAsaas($franqueado, $planoAssinatura);
                }
            }
        }
        $planoAssinatura = PlanoAssinaturaAfiliadoRegiao::where("id", $id)->first();

        $planoAssinatura['regiao'] = $regiaoAfiliado->regiao()->withTrashed()->first();
        $planoAssinatura['plano_assinatura'] = PlanoAssinaturaAfiliadoRegiao::withTrashed()->where("id", $regiaoAfiliado->plano_assinatura_afiliado_regiao_id)->first();
        if (isset($planoAssinatura['plano_assinatura']->data_cancelamento) &&  $planoAssinatura['plano_assinatura']->data_cancelamento != null) {
            $planoAssinatura = PlanoAssinaturaAfiliadoRegiao::withTrashed()->where("id", $planoAssinatura['plano_assinatura']->id)->first();
            if ($planoAssinatura) {
                $planoAssinatura->statusPlano = StatusPlano::$CANCELADO;
                $planoAssinatura->data_expiracao = null;
                $planoAssinatura->update();
            }

            $planoAssinatura['plano_assinatura'] = PlanoAssinaturaAfiliadoRegiao::withTrashed()->where("id", $regiaoAfiliado->plano_assinatura_afiliado_regiao_id)->first();
        } else if (isset($planoAssinatura['plano_assinatura']->id)) {
            $this->updateAssinaturaPlanoByIdInternal($planoAssinatura['plano_assinatura']->id);
        }
        $planoAssinatura['assinatura'] = ContratoAssinatura::withTrashed()->where("afiliado_id", $this->usuario_tipo_id)->where("plano_assinatura_afiliado_regiao_id", $regiaoAfiliado->plano_assinatura_afiliado_regiao_id)->orderBy("id", "desc")->first();

        return  $this->successResponse('Success', $planoAssinatura);
    }

    function updateAssinaturaPlanoByIdInternal($id)
    {
        $planoAssinatura = PlanoAssinaturaAfiliadoRegiao::where("id", $id)->first();
        if ($planoAssinatura) {
            $regiaoAfiliado = AfiliadoRegiao::where("plano_assinatura_afiliado_regiao_id", $planoAssinatura->id)->first();
            if ($regiaoAfiliado) {
                $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $regiaoAfiliado->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
                if ($franqueadoRegiao) {
                    $this->updateAssinaturas($planoAssinatura, $franqueadoRegiao->franqueado_id);
                    $this->updateAssinaturasAsaas($planoAssinatura, $franqueadoRegiao->franqueado_id);
                    $franqueado = Franqueado::where("id", $franqueadoRegiao->franqueado_id)->first();
                    $this->novaAssinaturaPlanoAsaas($franqueado, $planoAssinatura);
                }
            }
        }
    }

    function updateAssinaturaAsaasPlanoById($id)
    {
        $this->newLog("Atualizando assinaturas");
        $planoAssinatura = PlanoAssinaturaAfiliadoRegiao::where("id", $id)->first();
        $regiaoAfiliado = AfiliadoRegiao::where("plano_assinatura_afiliado_regiao_id", $planoAssinatura->id)->first();
        $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $regiaoAfiliado->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
        $d = null;
        if ($franqueadoRegiao) {
            $d = $this->updateAssinaturasAsaas($planoAssinatura, $franqueadoRegiao->franqueado_id);
        }
        return  $this->successResponse('Success', $d);
    }



    function updateAssinaturasAsaas($assinatura, $franqueado_id)
    {
        $tokenAsaas = Util::getTokenAsaasFranqueadoById($franqueado_id);
        $nova_data = null;
        if ($assinatura->asaas_assinatura_id && $tokenAsaas) {
            $assinatura_asaas = Asaas::getAssinaturaById($assinatura->asaas_assinatura_id, $tokenAsaas);
            if ($assinatura_asaas) {
                $assinatura->data_expiracao = $assinatura_asaas['nextDueDate'];
                if ($assinatura_asaas['status'] == "ACTIVE" && $assinatura['data_cancelamento'] == null) {
                    $statusPlano = 1;
                } else if ($assinatura_asaas['status'] == "EXPIRED" || $assinatura['data_cancelamento'] != null) {
                    $statusPlano = 2; //Cancelado
                    $assinatura->data_expiracao = null;
                }
                $assinatura->statusPlano = $statusPlano;
                $assinatura->update();
                return $assinatura;
            } else {
                $assinatura->statusPlano = StatusPlano::$INADIMPLENTE; //Em processo de cancelamento
                //$assinatura->statusPlano = StatusPlano::$ATIVO; //Em processo de cancelamento
                $assinatura->update();
                return $assinatura;
            }
        }

        return "sdfd";
        // if ($nova_data)
        //     $assinatura->data_expiracao = $nova_data;

        // return $assinatura;
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

                        //GERAR ASSINATURA ASAAS SE NÃO HOUVER


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




    function updateAssinaturaServicoById($id)
    {
        $this->newLog("Atualizando assinaturas serviço");
        $planoAssinatura = OrcamentoAssinatura::where("id", $id)->first();
        $orcamento = Orcamento::where("id", $planoAssinatura->orcamento_id)->first();
        $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $orcamento->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
        $d = null;
        if ($franqueadoRegiao) {
            $d = $this->updateAssinaturasServico($planoAssinatura, $franqueadoRegiao->franqueado_id, $orcamento);
        }
        return  $this->successResponse('Success', $d);
    }

    function updateAssinaturasServico($planoAssinatura, $franqueado_id, $orcamento)
    {
        $tokenAutentique = Util::getTokenAutentique($franqueado_id);
        $tokenAsaas = Util::getTokenAsaasFranqueadoById($franqueado_id);

        if ($tokenAutentique && $orcamento->documento_id_autentique  && $planoAssinatura->signed == null) {

            $res = DocumentosAutentique::listById($tokenAutentique, $orcamento->documento_id_autentique);

            if ($res && !isset($res->errors) && isset($res->data)) {
                $doc = $res->data->document;

                if ($orcamento->contrato_original == null)
                    $orcamento->contrato_original = isset($doc->files->original) ? $doc->files->original : null;

                if ($orcamento->contrato_assinado == null)
                    $orcamento->contrato_assinado = isset($doc->files->signed) ? $doc->files->signed : null;

                if (isset($doc->signatures))
                    foreach ($doc->signatures as $j => $assinatura) {
                        $assinaturaLocal = OrcamentoAssinatura::where("public_id", $assinatura->public_id)->where("orcamento_id", $orcamento->id)->first();
                        if ($assinaturaLocal) {
                            $assinaturaLocal->signed = isset($assinatura->signed->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->signed->created_at)) : null;
                            $assinaturaLocal->viewed = isset($assinatura->viewed->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->viewed->created_at)) : null;
                            $assinaturaLocal->rejected = isset($assinatura->rejected->created_at) ? date("Y-m-d H:i:s", strtotime($assinatura->rejected->created_at)) : null;
                            $assinaturaLocal->update();
                        }
                    }

                $assinaturasLocal = OrcamentoAssinatura::where("orcamento_id", $orcamento->id)->get();

                $assinadoFranqueado = false;
                $assinadoTestemunha1 = false;
                $assinadoTestemunha2 = false;
                $assinadoAfiliado = false;
                $assinadoSindico = false;

                foreach ($assinaturasLocal as $assLocal) {
                    if ($assLocal->tipo_usuario == "franqueado" && $assLocal->signed) {
                        $assinadoFranqueado = true;
                    } else if ($assLocal->tipo_usuario == "testemunha1" && $assLocal->signed) {
                        $assinadoTestemunha1 = true;
                        $orcamento->status_testemunha1 = StatusOrcamento::$CONTRATO_ASSINADO;
                    } else if ($assLocal->tipo_usuario == "afiliado" && $assLocal->signed) {
                        $assinadoAfiliado = true;
                        $orcamento->status_afiliado = StatusOrcamento::$CONTRATO_ASSINADO;
                    } else if ($assLocal->tipo_usuario == "testemunha2" && $assLocal->signed) {
                        $assinadoTestemunha2 = true;
                        $orcamento->status_testemunha2 = StatusOrcamento::$CONTRATO_ASSINADO;
                    } else if ($assLocal->tipo_usuario == "sindico" && $assLocal->signed) {
                        $assinadoSindico = true;
                        $orcamento->status_sindico = StatusOrcamento::$CONTRATO_ASSINADO;
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


                try {
                    if (($orcamento->status == StatusOrcamento::$ANALISANDO_CANDIDATOS
                            || $orcamento->status == StatusOrcamento::$ANALISANDO_ORCAMENTOS
                            || $orcamento->status == StatusOrcamento::$AGUARDANDO_CONTRATO
                            || $orcamento->status == StatusOrcamento::$CONTRATO_ASSINADO)
                        && $assinadoFranqueado && $assinadoTestemunha1 && $assinadoAfiliado && $assinadoTestemunha2 && $assinadoSindico
                    ) {
                        $orcamento->status = StatusOrcamento::$EM_EXECUCAO;
                        $orcamento->status_afiliado = StatusOrcamento::$EM_EXECUCAO;
                        $orcamento->status_sindico = StatusOrcamento::$EM_EXECUCAO;
                    }
                    $orcamento->update();
                } catch (Exception $e) {
                    return $e;
                }
            }
        } else {
            return null;
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
        $this->newLog("Afiliado tentando se inscrever em nova região");
        DB::beginTransaction();
        try {
            $regiao_id = $request['regiao_id'];
            $plano_id = $request['plano_id'];

            $planoOriginal = PlanoDisponivelFranqueado::where("id", $plano_id)->first();

            $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();

            $franqueado = Franqueado::where("id", $franqueadoRegiao->franqueado_id)->first();

            $franqueado_regiao_plano_disponibilizado_id = FranqueadoRegiaoPlanoDisponibilizado::where("franqueado_regiao_id", $franqueadoRegiao->id)->first()->id;


            $afiliado_regiao_teste = AfiliadoRegiao::where("afiliado_id", $this->usuario_tipo_id)->where("regiao_id", $regiao_id)->where("modo", Util::getModusOperandi())->orderBy("id", "desc")->first();

            $planoAssinaturaTeste = null;
            if ($afiliado_regiao_teste != null) {
                $planoAssinaturaTeste = PlanoAssinaturaAfiliadoRegiao::where("id", $afiliado_regiao_teste->plano_assinatura_afiliado_regiao_id)->first();
            }

            if ($afiliado_regiao_teste == null || $planoAssinaturaTeste == null || ($planoAssinaturaTeste != null && ($planoAssinaturaTeste->statusPlano == StatusPlano::$CANCELADO || $planoAssinaturaTeste->data_cancelamento != null))) {

                $planoAssintura = new PlanoAssinaturaAfiliadoRegiao();
                $planoAssintura->nome = $planoOriginal->nome;
                $planoAssintura->descricao = $planoOriginal->descricao;
                $planoAssintura->valor = $planoOriginal->valor;
                $planoAssintura->tipo_assinatura = 4; //Possibilidade de gerar contrato automático
                $planoAssintura->valor_comissao = $planoOriginal->valor_comissao;
                $planoAssintura->statusPlano = StatusPlano::$PENDENTE; //Ativo
                $planoAssintura->quantidade_meses_vigencia = Asaas::getMesesCiclo($planoOriginal->ciclo);
                $planoAssintura->dias_trial = $planoOriginal->dias_trial;
                $planoAssintura->desconto = $planoOriginal->desconto;
                $planoAssintura->isTerceirizada = $planoOriginal->isTerceirizada;
                $planoAssintura->ciclo = $planoOriginal->ciclo;
                $planoAssintura->gerenciado_plano_assas_franquia = 0;
                $planoAssintura->franqueado_regiao_plano_disponibilizado_id = $franqueado_regiao_plano_disponibilizado_id;
                $planoAssintura->save();


                $afiliado_regiao = new AfiliadoRegiao();
                $afiliado_regiao->afiliado_id = $this->usuario_tipo_id;
                $afiliado_regiao->regiao_id = $regiao_id;
                $afiliado_regiao->plano_assinatura_afiliado_regiao_id = $planoAssintura->id;
                $afiliado_regiao->modo = Util::getModusOperandi();
                $afiliado_regiao->save();

                DB::commit();

                $afiliado = Afiliado::withTrashed()->where("id", $afiliado_regiao->afiliado_id)->withTrashed()->first();
                Notificacao::painelNotificarAfiliadoNovaRegiao($afiliado, $afiliado_regiao);
                return $this->successResponse('Afiliado regiao atualizado', $planoAssintura);
            } else {
                DB::rollback();
                return $this->errorResponse([['error_code' => "regiao-afiliada", "error_message" => "Você já é afiliado desta região."]], 403);
            }
            DB::rollback();
            return $this->errorResponse("Erro", 403);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e, 403);
        }
    }

    public function novaAssinaturaPlanoAsaas($franqueado, $planoAssintura)
    {
        if ($planoAssintura->valor >= 10 && $planoAssintura->gerenciado_plano_assas_franquia == 0 && $planoAssintura->asaas_assinatura_id == null && $planoAssintura->status_afiliado == 1 && ($planoAssintura->statusPlano == StatusPlano::$PENDENTE || $planoAssintura->statusPlano == StatusPlano::$ATIVO)) {
            $afiliadoAsaas = AfiliadoFranqueadoAsaas::where("afiliado_id", $this->usuario_tipo_id)->where("franqueado_id", $franqueado->id)->orderBy("id", "desc")->first();

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
                    Notificacao::painelNotificarAfiliadoPlanoAtivo($afiliado);
                    DB::commit();
                    return $planoAssintura;
                } else {
                    $resNewCustomer = Asaas::novoCustomer($afiliado, Util::getTokenAsaasFranqueadoById($franqueado->id), true, $franqueado->id);
                    $afiliadoAsaas = AfiliadoFranqueadoAsaas::where("afiliado_id", $this->usuario_tipo_id)->where("franqueado_id", $franqueado->id)->orderBy("id", "desc")->first();
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

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\AfiliadoRegiao  $afiliadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function partial_update(Request $request)
    {
        try {
            $afiliado_regiao = $this->class_name::where('afiliado_id', $this->usuario_tipo_id)->get();
            // $afiliado_regiao->regiao_id = $this->getValueRequest($request, $afiliado_regiao, 'regiao_id', true);
            $afiliado_regiao->plano_assinatura_afiliado_regiao_id = $this->getValueRequest($request, $afiliado_regiao, 'plano_assinatura_afiliado_regiao_id', true);
            // $afiliado_regiao->data_pagamento_plano = $this->getValueRequest($request, $afiliado_regiao, 'data_pagamento_plano', true);
            // $afiliado_regiao->data_expiracao_plano = $this->getValueRequest($request, $afiliado_regiao, 'data_expiracao_plano', true);
            // $afiliado_regiao->update();
            if ($afiliado_regiao->plano_assinatura_afiliado_regiao_id > 0) {
                $dados = PlanoAssinaturaAfiliadoRegiao::where('franqueado_regiao_plano_disponibilizado_id', $afiliado_regiao->plano_assinatura_afiliado_regiao_id)->first();
                $assinatura = (object) [
                    "customer" => $this->getAsaasDebugToken($dados),
                    "billingType" => 'UNDEFINED',
                    "value" => $dados->valor,
                    "nextDueDate" => new Date(),
                    "description" => $dados->descricao,
                    "cycle" => "MONTHLY",

                ];
                return $this->criarAssinatura($assinatura, null);
            }
            $data = new ResourcesAfiliadoRegiao($afiliado_regiao);
            return $this->successResponse('Afiliado regiao atualizado', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\AfiliadoRegiao  $afiliadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function assinar($id)
    {
        return "Roi";
    }

    private function getAsaasDebugToken($franqueado_regiao_plano)
    {
        $token = FranqueadoRegiaoPlanoDisponibilizado::join('franqueado_regiao', 'franqueado_regiao.id', 'franqueado_regiao_plano_disponibilizado.franqueado_regiao_id')
            ->join('franqueado', 'franqueado.id', 'franqueado_regiao.franqueado_id')
            ->select('franqueado.token_asaas_debug')
            ->where('franqueado_regiao_plano_disponibilizado.id', $franqueado_regiao_plano->franqueado_regiao_plano_disponibilizado_id)
            ->first();
        return $token;
    }




    private function enviarEmailCobranca()
    {
    }




    private function criarAssinatura($assinatura, $token)
    {
        $this->newLog("Criar assinatura asaas");
        return Asaas::criarAssinatura($assinatura, $token);
    }
}
