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
 * Class PlanoDisponivelFranqueado
 * 
 * @property int $id
 * @property string $nome
 * @property string|null $descricao
 * @property float $valor
 * @property float $valor_comissao
 * @property int $statusPlano
 * @property int $quantidade_meses_vigencia
 * @property int $dias_trial
 * @property int $usuario_sistema_admin_id
 * @property int $regiao_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Regiao $regiao
 * @property UsuarioSistemaAdmin $usuario_sistema_admin
 * @property Collection|FranqueadoRegiaoPlanoDisponibilizado[] $franqueado_regiao_plano_disponibilizados
 *
 * @package App\Models
 */
class PlanoDisponivelFranqueado extends Model
{
	use SoftDeletes;
	protected $table = 'plano_disponivel_franqueado';
	public $timestamps = false;

	protected $casts = [
		'valor' => 'float',
		'valor_comissao' => 'float',
		'statusPlano' => 'int',
		'quantidade_meses_vigencia' => 'int',
		'dias_trial' => 'int',
		'usuario_sistema_admin_id' => 'int',
		'regiao_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'descricao',
		'valor',
		'valor_comissao',
		'statusPlano',
		'quantidade_meses_vigencia',
		'dias_trial',
		'usuario_sistema_admin_id',
		'regiao_id',
		'data_cadastro',
		'data_atualizacao'
	];

	public function regiao()
	{
		return $this->belongsTo(Regiao::class);
	}

	public function usuario_sistema_admin()
	{
		return $this->belongsTo(UsuarioSistemaAdmin::class);
	}

	public function franqueado_regiao_plano_disponibilizados()
	{
		return $this->hasMany(FranqueadoRegiaoPlanoDisponibilizado::class);
	}
}
