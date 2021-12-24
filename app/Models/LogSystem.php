<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

/**
 * Class Categorium
 * 
 * @property int $id
 * @property string $nome
 * @property string $descricao
 * @property string $chave_url
 * @property string $imagem
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Collection|AfiliadoCategorium[] $afiliado_categoria
 * @property Collection|Orcamento[] $orcamentos
 *
 * @package App\Models
 */
class LogSystem extends Model
{
	protected $table = 'log_system';
	public $timestamps = false;

	protected $fillable = [
		'descricao',
		'endpoint',
		'usuario_app_id',
		'data_cadastro',
		'metodo',
		'body',
		'response',
		'status_response',
		'messagem_response',
		'data_atualizacao',
		"time_inicio",
		"time_final",
		'delta_time'
	];

	public static function send($descricao)
	{
		$dados = Request::all();
		if (isset($dados['senha'])) {
			unset($dados['senha']);
		}

		if (isset($dados['file'])) {
			unset($dados['file']);
		}

		$log = new LogSystem([
			"data_cadastro" => Carbon::now(),
			"time_inicio" => time(),
			"descricao" => $descricao,
			"endpoint" => Request::url(),
			"metodo" => Request::method(),
			"usuario_app_id" => !empty(Auth::guard('api')->user()) ? Auth::guard('api')->user()->id : null,
			"body" => json_encode($dados)
		]);
		$log->save();
		return $log;
	}

	public function updateResponse($response = null, $mensagem_response = "", $status = 200)
	{
		if (isset($response['senha'])) {
			unset($response['senha']);
		}
		$micro = time();
		$delta = $micro - $this->time_inicio;
		return $this->update([
			"response" => json_encode($response),
			"status_response" => $status,
			"messagem_response" => $mensagem_response,
			"data_atualizacao" => Carbon::now(),
			"time_final" => $micro,
			"delta_time" => $delta
		]);
	}
}
