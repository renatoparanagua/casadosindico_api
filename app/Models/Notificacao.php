<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Models\BO\OrcamentoBO;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Parceiro
 * 
 * @property int $id
 * @property string|null $nome
 * @property string|null $logo
 * @property string|null $link
 * @property string|null $email
 * @property string|null $nome_responsavel
 * @property string|null $telefone
 * @property string $status
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Notificacao extends Model
{
	use SoftDeletes;
	protected $table = 'notificacoes';
	public $timestamps = false;


	protected $fillable = [
		'titulo',
		'corpo',
		'isSendEmail',
		'isSendNotification',
		'link_interno_app',
		'link_imagem',
		'link_externo_app',
		'data_visualizacao',
		'removido_app',
		'usuario_app_id',
		'link_interno_app_label',
		'link_externo_app_label',
		'param_link_interno',
		'param_name',
		'tipo_usuario'
	];

	public static function painelNotificarAfiliadoNovaSolicitacao($orcamento, $afiliado)
	{
		try {
			Notificacao::create([
				"titulo" => "Nova solicitação #" . $orcamento->id,
				"corpo" => "Nova solicitação para categoria " . $orcamento->categoria()->withTrashed()->first()->nome . ". Condomínio " . $orcamento->condominio()->withTrashed()->first()->nome,
				"link_interno_app" => null,
				"tipo_usuario" => "afiliado",
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $afiliado->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}

	public static function painelNotificarAfiliadoAlteracaoStatus($orcamento, $afiliado, $mensagem = "")
	{
		try {
			Notificacao::create([
				"titulo" => "Solicitação #" . $orcamento->id .  " foi alterada",
				"corpo" => $mensagem,
				"link_interno_app" => null,
				"tipo_usuario" => "afiliado",
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $afiliado->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}

	public static function painelNotificarSindicoAlteracaoStatus($orcamento, $sindico, $mensagem = "")
	{
		try {
			Notificacao::create([
				"titulo" => "Solicitação #" . $orcamento->id .  " foi alterada",
				"corpo" => $mensagem,
				"link_interno_app" => null,
				"tipo_usuario" => "sindico",
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $sindico->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}

	public static function painelNotificarAfiliadoEscolhido($orcamento, $afiliado, $mensagem = null)
	{
		try {
			Notificacao::create([
				"titulo" => "Você foi selecionado para o serviço de " . $orcamento->nome . ". Ref.: #" . $orcamento->id,
				"corpo" => $mensagem == null ? "Parabéns, você foi selecionado para o serviço " . $orcamento->nome . ". Ref.: #" . $orcamento->id . ". Aguarde o contrato." : $mensagem,
				"link_interno_app" => null,
				"tipo_usuario" => null,
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $afiliado->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}

	public static function painelNotificarSindicioDeAfiliadoEscolhido($orcamento, $afiliado, $sindico, $mensagem = null)
	{
		try {
			Notificacao::create([
				"titulo" => $afiliado->nome_fantasia . " selecionada para " . $orcamento->nome,
				"corpo" => $mensagem == null ? "Você selecionou a empresa " . $afiliado->nome_fantasia . " para o serviço " . $orcamento->nome . ". Ref.: " . $orcamento->id : $mensagem,
				"link_interno_app" => null,
				"tipo_usuario" => "sindico",
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $sindico->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}

	public static function painelNotificarSindicioNovaVistoria($orcamento, $sindico, $mensagem = null)
	{
		try {
			Notificacao::create([
				"titulo" => "Vistoria para " . $orcamento->nome,
				"corpo" => "Uma vistoria foi adicionada em sua solicitação de referência " . $orcamento->id . ". Em breve nosso vistoriador irá agendar.",
				"link_interno_app" => null,
				"tipo_usuario" => "sindico",
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $sindico->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}

	public static function painelNotificarOrcamentoEmExecucao($orcamento, $tipo_usuario, $usuario_app_id)
	{
		try {
			Notificacao::create([
				"titulo" => "Solicitação #" . $orcamento->id . " em Execução",
				"corpo" => "A solicitação #" . $orcamento->id . " - " . $orcamento->nome . " entrou em fase de execução, pois foi assinada por todos.",
				"link_interno_app" => null,
				"tipo_usuario" => $tipo_usuario,
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}


	public static function painelNotificarSindicioNovoInteressdo($orcamento, $sindico, $afiliado, $mensagem = null)
	{
		try {
			Notificacao::create([
				"titulo" => "Solicitação " . $orcamento->nome . " - Novo interessado",
				"corpo" => "O afiliado " . ($afiliado->nome_fantasia ? $afiliado->nome_fantasia : $afiliado->razao_social) . " está interessado na solicitação " . $orcamento->id,
				"link_interno_app" => null,
				"tipo_usuario" => "sindico",
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $sindico->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}



	public static function painelNotificarAfiliadoSindicoAceita($orcamento, $sindico, $afiliado, $mensagem = null)
	{
		try {
			Notificacao::create([
				"titulo" => "Solicitação " . $orcamento->nome . " está aceitando orçamentos",
				"corpo" => "O síndico está aceitando orçamentos da Solicitação " . $orcamento->nome . ". Ref.: " . $orcamento->id,
				"link_interno_app" => null,
				"tipo_usuario" => "afiliado",
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $afiliado->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}


	public static function painelNotificarAfiliadoSindicoRecusa($orcamento, $sindico, $afiliado, $mensagem = null)
	{
		try {
			Notificacao::create([
				"titulo" => "Solicitação " . $orcamento->nome . " descartada",
				"corpo" => "O síndico selecionou outro afiliado para a solicitação Ref.: " . $orcamento->id . ". Você não verá mais essa solicitação em sua listagem.",
				"link_interno_app" => null,
				"tipo_usuario" => "afiliado",
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $afiliado->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}



	public static function painelNotificarAfiliadoNaoEscolhido($orcamento, $afiliado, $mensagem = null)
	{
		try {
			Notificacao::create([
				"titulo" => "Outro afiliado foi selecionado para o serviço de " . $orcamento->nome,
				"corpo" => "Não foi desta vez. Outro afiliado foi selecionado para o serviço de " . $orcamento->nome . ". Ref.: #" . $orcamento->id . ". Essa solicitação não ficará mais visível para você.",
				"link_interno_app" => null,
				"tipo_usuario" => "afiliado",
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $afiliado->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}


	public static function painelNotificarAfiliadoNovaRegiao($afiliado, $afiliado_regiao, $mensagem = null)
	{
		try {
			Notificacao::create([
				"titulo" => "Você afiliou a uma região",
				"corpo" => "Parabéns, você se afiliou a uma nova região. Aguarde o seu contrato ficar disponível para assinatura",
				"tipo_usuario" => "afiliado",
				"link_interno_app" => null,
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $afiliado->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}



	public static function painelNotificarAfiliadRegiaoCancelada($orcamento, $afiliado, $mensagem = null)
	{
		try {
			Notificacao::create([
				"titulo" => "Outro afiliado foi selecionado para o serviço de " . $orcamento->nome,
				"corpo" => "Não foi desta vez. Outro afiliado foi selecionado para o serviço de " . $orcamento->nome . ". Ref.: #" . $orcamento->id . ". Essa solicitação não ficará mais visível para você.",
				"link_interno_app" => null,
				"tipo_usuario" => "afiliado",
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $afiliado->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}


	public static function painelNotificarAfiliadRegiaoContratoDisponivel($orcamento, $afiliado, $mensagem = null)
	{
		try {
			Notificacao::create([
				"titulo" => "Outro afiliado foi selecionado para o serviço de " . $orcamento->nome,
				"corpo" => "Não foi desta vez. Outro afiliado foi selecionado para o serviço de " . $orcamento->nome . ". Ref.: #" . $orcamento->id . ". Essa solicitação não ficará mais visível para você.",
				"link_interno_app" => null,
				"tipo_usuario" => "afiliado",
				"link_interno_app_label" => null,
				"param_name" => null,
				"usuario_app_id" => $afiliado->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}


	public static function painelNotificarAfiliadoPlanoAtivo($afiliado, $mensagem = null)
	{
		try {
			Notificacao::create([
				"titulo" => "Plano de assinatura ativo",
				"corpo" => "Parabéns, plano de assinatura está ativo.",
				"tipo_usuario" => "afiliado",
				"link_interno_app" => "afiliado/orcamentos",
				"link_interno_app_label" => "Ver solicitações",
				"param_name" => null,
				"usuario_app_id" => $afiliado->usuario_app_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}


	public static function painelNotificarUsuarioBoasVindas($nome, $tipo_usuario = null, $usuario_id, $mensagem = null)
	{
		try {
			Notificacao::create([
				"titulo" => "Seja bem vindo a Casa do Síndico",
				"corpo" => "$nome, parabéns pela escolha. Nossa empresa está muito feliz em ter você conosco.",
				"tipo_usuario" => $tipo_usuario,
				"link_imagem" => "https://casadosindico.srv.br/imagem-boas-vindas/imagem.png",
				"link_interno_app_label" => "Ver contrato",
				"param_name" => "contrato_selecionado",
				"usuario_app_id" => $usuario_id,
				"param_link_interno" => null
			]);
		} catch (Exception $e) {
		}
	}
}
