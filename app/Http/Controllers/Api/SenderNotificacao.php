<?php

namespace App\Http\Controllers\Api;

use App\Models\Categoria;
use App\Models\Configuracao;
use App\Models\Orcamento;
use App\Util\Formatacao;

class SenderNotificacao
{
    public static function getConfig()
    {
        return Configuracao::orderBy("id", "DESC")->first();
    }

    public static function enviarNotificacaoNovaSolicitacao($id, $token_user, $nome_condominio)
    {
        $orcamento = Orcamento::where("id", $id)->first();
        $nome_categoria = "";
        if ($orcamento) {
            $categoria = Categoria::where("id", $orcamento->categoria_id)->first();
            if ($categoria) {
                $nome_categoria = $categoria->nome;
            }
        }
        $config = self::getConfig();
        FCM::send($token_user, $config->nome_empresa, "Você possui nova solicitação categoria $nome_categoria para o condomínio $nome_condominio. Ref.: #$id", ["tipo" => "navegacao", "texto_botao" => "Ver", "url_botao" => "afiliado/orcamentos"]);
    }

    public static function novoInteressado($id, $token_user, $razao_social)
    {
        $config = self::getConfig();
        FCM::send($token_user, $config->nome_empresa, "$razao_social se interessou pela solicitação #$id", ["tipo" => "navegacao", "texto_botao" => "Ver", "url_botao" => "sindico/orcamentos"]);
    }

    public static function orcamentoEmExecucao($token_user, $orcamento)
    {
        $config = self::getConfig();
        FCM::send($token_user, $config->nome_empresa, "A solicitação #" . $orcamento->id . " - " . $orcamento->nome . " entrou em fase de execução.", ["tipo" => "navegacao", "texto_botao" => "Ver", "url_botao" => "sindico/orcamentos"]);
    }

    public static function aceitarOrcamento($id, $token_user, $nome_sindico, $solicitacao)
    {
        $config = self::getConfig();
        FCM::send($token_user, $config->nome_empresa, "O síndico $nome_sindico deseja receber sua visita para um orçamento para a solicitação #$id", ["tipo" => "navegacao", "texto_botao" => "Ver", "url_botao" => "afiliado/orcamentos", "orcamento" => $solicitacao]);
    }

    public static function recusarOrcamento($id, $token_user, $solicitacao)
    {
        $config = self::getConfig();
        FCM::send($token_user, $config->nome_empresa, "O síndico rejeitou seu orçamento para solicitação Ref.: #$id", ["tipo" => "navegacao", "texto_botao" => "Ver", "url_botao" => "afiliado/orcamentos", "orcamento" => $solicitacao]);
    }

    public static function enviarNotificacaoAfiliadoContratado($token_user, $id)
    {
        $config = self::getConfig();
        FCM::send($token_user, $config->nome_empresa, "Você acaba de ser escolhido por um síndico para realizar o serviço de solicitação #$id. Agora, aguarde o contrato.", ["tipo" => "navegacao", "texto_botao" => "Ver", "url_botao" => "afiliado/orcamentos"]);
    }

    public static function enviarNotificacaoAlteracaoStatusOrcamento($token_user, $orcamento, $tipo_usuario, $valor = null)
    {
        $config = self::getConfig();
        $mensagem = "";
        if ($tipo_usuario == "sindico") {
            if ($orcamento->status_afiliado == 5) {
                $mensagem = "O prestador de serviço da solicitação #" . $orcamento->id . " CONCLUIU o serviço.";
            } elseif ($orcamento->status_afiliado == 9) {
                $mensagem = "O prestador de serviço da solicitação #" . $orcamento->id . " CANCELOU o serviço.";
            }
        } elseif ($tipo_usuario == "afiliado") {
            if ($orcamento->status_sindico == 5) {
                $mensagem = "O síndico do serviço da solicitação #" . $orcamento->id . " CONCLUIU o serviço.";
            } elseif ($orcamento->status_sindico == 9) {
                $mensagem = "O síndico do serviço da solicitação #" . $orcamento->id . " CANCELOU o serviço.";
            } elseif ($orcamento->status_sindico == 2) {
                if ($valor)
                    $mensagem = "O síndico do serviço da solicitação #" . $orcamento->id . " está analisando os orçamentos. Sua proposta foi de R$" . $valor . ".";
                else
                    $mensagem = "O síndico do serviço da solicitação #" . $orcamento->id . " está analisando os orçamentos. Você ainda não encaminhou sua proposta";
            }
        }

        if ($mensagem)
            FCM::send($token_user, $config->nome_empresa, $mensagem, ["tipo" => "alert", "orcamento" => $orcamento]);

        return $mensagem;
    }


    public static function enviarPreencheuValorOrcamento($token_user, $id, $afiliado_nome)
    {
        $config = self::getConfig();
        FCM::send($token_user, $config->nome_empresa, "O afiliado $afiliado_nome, enviou uma proposta e valor para o serviço de solicitação #$id.", ["tipo" => "navegacao", "texto_botao" => "Ver", "url_botao" => "sindico/orcamentos"]);
    }

    //Vistorias
    //Enviar para vistoriador
    public static function novaVistoria($token_user_vistoriador, $vistoriador)
    {
        $config = self::getConfig();
        FCM::send($token_user_vistoriador, $config->nome_empresa, "Nova vistoria solicitada", ["tipo" => "alert"]);
    }

    //Vistorias
    //Enviar para sindico
    public static function vistoriaAgendada($token_user_sindico, $sindico, $vistoria, $vistoriador)
    {
        $config = self::getConfig();
        FCM::send($token_user_sindico, $config->nome_empresa, "Vistoria agendada para " . Formatacao::data($vistoria->data_vistoria, false, false) . " pelo vistoriador " . $vistoriador->nome . ". Solicitação Ref.:" . $vistoria->orcamento_id, ["tipo" => "alert"]);
    }

    public static function vistoriadorCheckin($token_user_sindico, $sindico, $vistoria, $vistoriador)
    {
        $config = self::getConfig();
        FCM::send($token_user_sindico, $config->nome_empresa, "O vistoriador " . $vistoriador->nome . " está no condomínio. Serviço Ref.: " . $vistoria->orcamento_id, ["tipo" => "alert"]);
    }

    public static function vistoriadorCheckout($token_user_sindico, $sindico, $vistoria, $vistoriador)
    {
        $config = self::getConfig();
        FCM::send($token_user_sindico, $config->nome_empresa, "O vistoriador " . $vistoriador->nome . " está saindo ou já saiu do condomínio. Serviço Ref.: " . $vistoria->orcamento_id, ["tipo" => "alert"]);
    }
}
