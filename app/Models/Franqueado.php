<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Franqueado
 * 
 * @property int $id
 * @property string $nome
 * @property string|null $email
 * @property string|null $senha
 * @property string|null $cnpj
 * @property string|null $inscricao_estadual
 * @property string|null $inscricao_municipal
 * @property string|null $cpf_responsavel
 * @property string|null $rg_responsavel
 * @property string|null $profissao_responsavel
 * @property string|null $telefone_responsavel
 * @property string|null $cep
 * @property string|null $estado
 * @property string|null $cidade
 * @property string|null $bairro
 * @property string|null $rua
 * @property string|null $token_asaas_producao
 * @property string|null $token_asaas_debug
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Collection|Regiao[] $regiaos
 * @property Collection|Sindico[] $sindicos
 * @property Collection|Vistoriador[] $vistoriadors
 *
 * @package App\Models
 */
class Franqueado extends Model
{
	use SoftDeletes;
	protected $table = 'franqueado';
	public $timestamps = false;

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'email',
		'senha',
		'cnpj',
		'inscricao_estadual',
		'inscricao_municipal',
		'cpf_responsavel',
		'rg_responsavel',
		'profissao_responsavel',
		'telefone_responsavel',
		'cep',
		'estado',
		'cidade',
		'bairro',
		'rua',
		'token_asaas_producao',
		'token_asaas_debug',
		'data_cadastro',
		'data_atualizacao'
	];

	public function regiaos()
	{
		return $this->belongsToMany(Regiao::class)
					->withPivot('id', 'status', 'usuario_sistema_admin_id', 'data_inicio_atividade', 'data_fim_atividade', 'data_cadastro', 'data_atualizacao', 'deleted_at');
	}

	public function sindicos()
	{
		return $this->hasMany(Sindico::class);
	}

	public function vistoriadors()
	{
		return $this->hasMany(Vistoriador::class);
	}
}
