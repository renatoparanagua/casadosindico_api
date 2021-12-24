<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Api\Controller;
use App\Models\Afiliado;
use App\Models\AfiliadoFranqueadoAsaas;
use App\Models\AfiliadoRegiao;
use App\Models\FranqueadoRegiao;
use App\Models\Notificacao;
use App\Models\Orcamento;
use App\Models\OrcamentoAssinatura;
use App\Models\PlanoAssinaturaAfiliadoRegiao;
use App\Models\Sindico;
use App\Models\UsuarioApp;
use App\Util\Autentique\DocumentosAutentique;
use App\Util\StatusAsass;
use App\Util\StatusAssinaturaPlano;
use App\Util\StatusOrcamento;
use App\Util\Util;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;

class IntegradorController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request, null);
    }

    public function atualizarAssinaturasAsaas($token)
    {
        set_time_limit(0);
        $timeInicio = time();
        if ($token == "naopossonempensar") {
            $afiliadosFranqueados = AfiliadoFranqueadoAsaas::get();
            foreach ($afiliadosFranqueados as $afiliadoFranqueado) {
                $token_franqueado = Util::getTokenAsaasFranqueadoById($afiliadoFranqueado->franqueado_id);
                $res = Asaas::getCobrancasByStatus($afiliadoFranqueado->asaas_customer_id, StatusAsass::$OVERDUE, $token_franqueado);
                $cobrancasVencidas = Asaas::extractCobrancas($res);
                $afiliadoFranqueado->asaas_cobrancas_vencidas = json_encode($cobrancasVencidas);

                $afiliadoRegioes = AfiliadoRegiao::where("afiliado_id", $afiliadoFranqueado->afiliado_id)->get();
                foreach ($afiliadoRegioes as $afiliadoRegiao) {
                    $planoAssinatura = PlanoAssinaturaAfiliadoRegiao::where("id", $afiliadoRegiao->plano_assinatura_afiliado_regiao_id)->first();
                    $cobrancasAssinaturas = Asaas::extractCobrancas(Asaas::getCobrancasByAssinaturaByStatus($planoAssinatura->asaas_assinatura_id, StatusAsass::$OVERDUE, $token_franqueado));
                    $planoAssinatura->asaas_cobrancas_vencidas = json_encode($cobrancasAssinaturas);
                    $planoAssinatura->asaas_ultima_verificacao = Carbon::now();
                    $planoAssinatura->save();
                }
                $afiliadoFranqueado->asaas_ultima_verificacao = Carbon::now();
                $afiliadoFranqueado->save();
            }

            $timeFim = time();
            dd("Tempo: " . ($timeFim - $timeInicio) . " segundos.");
        }
        dd("Falhou");
    }

    public function update_assinaturas_autentique($token)
    {
        set_time_limit(0);
        $timeInicio = time();
        if ($token == "naopossonempensar") {
            $orcamentos = Orcamento::whereIn("status", [StatusOrcamento::$ANALISANDO_CANDIDATOS, StatusOrcamento::$ANALISANDO_ORCAMENTOS, StatusOrcamento::$AGUARDANDO_CONTRATO, StatusOrcamento::$CONTRATO_ASSINADO])->get();

            foreach ($orcamentos as $orcamento) {
                if (
                    $orcamento->status == StatusOrcamento::$ANALISANDO_CANDIDATOS
                    || $orcamento->status == StatusOrcamento::$ANALISANDO_ORCAMENTOS
                    || $orcamento->status == StatusOrcamento::$AGUARDANDO_CONTRATO
                    || $orcamento->status == StatusOrcamento::$CONTRATO_ASSINADO
                ) {
                    $orcamentoAssinaturas = OrcamentoAssinatura::where("orcamento_id", $orcamento->id)->get();
                    foreach ($orcamentoAssinaturas as $orcamentoAssinatura) {
                        $this->updateAssinaturaServicoById($orcamentoAssinatura->id);
                    }
                }
            }

            $timeFim = time();
            dd("Tempo: " . ($timeFim - $timeInicio) . " segundos.");
        }
        dd("Falhou");
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
                    if ((($orcamento->status == StatusOrcamento::$ANALISANDO_CANDIDATOS
                        || $orcamento->status == StatusOrcamento::$ANALISANDO_ORCAMENTOS
                        || $orcamento->status == StatusOrcamento::$AGUARDANDO_CONTRATO
                        || $orcamento->status == StatusOrcamento::$CONTRATO_ASSINADO)
                        && $assinadoFranqueado && $assinadoTestemunha1 && $assinadoAfiliado && $assinadoTestemunha2 && $assinadoSindico)) {
                        $orcamento->status = StatusOrcamento::$EM_EXECUCAO;
                        $orcamento->status_afiliado = StatusOrcamento::$EM_EXECUCAO;
                        $orcamento->status_sindico = StatusOrcamento::$EM_EXECUCAO;

                        $sindico = Sindico::where("id", $orcamento->sindico_id)->first();
                        if ($sindico) {
                            $usuarioSindico = UsuarioApp::where("id", $sindico->usuario_app_id)->first();
                            if ($usuarioSindico) {
                                if ($usuarioSindico->token_notification) {
                                    SenderNotificacao::orcamentoEmExecucao($usuarioSindico->token_notification, $orcamento);
                                }
                                Notificacao::painelNotificarOrcamentoEmExecucao($orcamento, "sindico", $usuarioSindico->id);
                            }
                        }

                        $afiliado = Afiliado::where("id", $orcamento->afiliado_id)->first();

                        if ($afiliado) {
                            $usuarioAfiliado = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
                            if ($usuarioAfiliado) {
                                if ($usuarioAfiliado->token_notification) {
                                    SenderNotificacao::orcamentoEmExecucao($usuarioAfiliado->token_notification, $orcamento);
                                }
                                Notificacao::painelNotificarOrcamentoEmExecucao($orcamento, "afiliado", $usuarioAfiliado->id);
                            }
                        }
                        echo $orcamento->id . "<br>";
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
}
