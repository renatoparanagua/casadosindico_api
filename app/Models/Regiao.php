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
 * Class Regiao
 * 
 * @property int $id
 * @property string $nome
 * @property string $descricao
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Collection|Afiliado[] $afiliados
 * @property Collection|Bairro[] $bairros
 * @property Collection|Franqueado[] $franqueados
 * @property Collection|Orcamento[] $orcamentos
 * @property Collection|PlanoDisponivelFranqueado[] $plano_disponivel_franqueados
 *
 * @package App\Models
 */
class Regiao extends Model
{
	use SoftDeletes;
	protected $table = 'regiao';
	public $timestamps = false;

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'descricao',
		'data_cadastro',
		'data_atualizacao'
	];

	public function afiliados()
	{
		return $this->belongsToMany(Afiliado::class)
					->withPivot('id', 'plano_assinatura_afiliado_regiao_id', 'data_pagamento_plano', 'data_expiracao_plano', 'data_cadastro', 'data_atualizacao', 'deleted_at');
	}

	public function bairros()
	{
		return $this->hasMany(Bairro::class);
	}

	public function franqueados()
	{
		return $this->belongsToMany(Franqueado::class)
					->withPivot('id', 'status', 'usuario_sistema_admin_id', 'data_inicio_atividade', 'data_fim_atividade', 'data_cadastro', 'data_atualizacao', 'deleted_at');
	}

	public function orcamentos()
	{
		return $this->hasMany(Orcamento::class);
	}

	public function plano_disponivel_franqueados()
	{
		return $this->hasMany(PlanoDisponivelFranqueado::class);
	}
}
