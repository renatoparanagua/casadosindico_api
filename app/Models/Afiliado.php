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
 * Class Afiliado
 * 
 * @property int $id
 * @property string $razao_social
 * @property string $nome_fantasia
 * @property string|null $telefone
 * @property string|null $email
 * @property string $cnpj
 * @property string|null $cartao_cnpj
 * @property string|null $inscricao_estadual
 * @property string|null $inscricao_municipal
 * @property string|null $cep
 * @property string|null $estado
 * @property string|null $cidade
 * @property string|null $bairro
 * @property string|null $rua
 * @property string|null $numero
 * @property string|null $complemento
 * @property string|null $rumo_atividade
 * @property int|null $numero_funcionarios
 * @property string|null $logo
 * @property string $status
 * @property int $usuario_app_id
 * @property Carbon|null $data_contrato
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property UsuarioApp $usuario_app
 * @property Collection|AfiliadoCategorium[] $afiliado_categoria
 * @property Collection|Orcamento[] $orcamentos
 * @property Collection|Regiao[] $regiaos
 * @property Collection|CartaoCnpj[] $cartao_cnpjs
 * @property Collection|Contrato[] $contratos
 * @property Collection|ContratoSocial[] $contrato_socials
 * @property Collection|ResponsavelAfiliado[] $responsavel_afiliados
 *
 * @package App\Models
 */
class Afiliado extends Model
{
	use SoftDeletes;
	protected $table = 'afiliado';
	public $timestamps = false;

	/**
	 * Variables update_at, created_at and deleted_at.
	 */
	const CREATED_AT = 'data_cadastro';
	const UPDATED_AT = 'data_atualizacao';

	protected $casts = [
		'numero_funcionarios' => 'int',
		'usuario_app_id' => 'int'
	];

	protected $dates = [
		'data_contrato',
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'razao_social',
		'nome_fantasia',
		'telefone',
		'email',
		'cnpj',
		'cartao_cnpj',
		'inscricao_estadual',
		'inscricao_municipal',
		'cep',
		'estado',
		'cidade',
		'bairro',
		'rua',
		'numero',
		'complemento',
		'rumo_atividade',
		'numero_funcionarios',
		'logo',
		'status',
		'usuario_app_id',
		'data_contrato',
		'data_cadastro',
		'data_atualizacao'
	];

	public function usuario_app()
	{
		return $this->belongsTo(UsuarioApp::class);
	}

	public function afiliado_categoria()
	{
		return $this->hasMany(AfiliadoCategorium::class);
	}

	public function orcamentos()
	{
		return $this->hasMany(Orcamento::class);
	}

	public function regiaos()
	{
		return $this->belongsToMany(Regiao::class)
			->withPivot('id', 'plano_assinatura_afiliado_regiao_id', 'data_pagamento_plano', 'data_expiracao_plano', 'data_cadastro', 'data_atualizacao', 'deleted_at');
	}

	public function cartao_cnpjs()
	{
		return $this->hasMany(CartaoCnpj::class);
	}

	public function contratos()
	{
		return $this->hasMany(Contrato::class);
	}

	public function contrato_socials()
	{
		return $this->hasMany(ContratoSocial::class);
	}

	public function responsavel_afiliados()
	{
		return $this->hasMany(ResponsavelAfiliado::class);
	}
}
