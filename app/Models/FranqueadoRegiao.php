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
 * Class FranqueadoRegiao
 * 
 * @property int $id
 * @property string $status
 * @property int $franqueado_id
 * @property int $regiao_id
 * @property int $usuario_sistema_admin_id
 * @property Carbon|null $data_inicio_atividade
 * @property Carbon|null $data_fim_atividade
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Franqueado $franqueado
 * @property Regiao $regiao
 * @property UsuarioSistemaAdmin $usuario_sistema_admin
 * @property Collection|FranqueadoRegiaoPlanoDisponibilizado[] $franqueado_regiao_plano_disponibilizados
 *
 * @package App\Models
 */
class FranqueadoRegiao extends Model
{
	use SoftDeletes;
	protected $table = 'franqueado_regiao';
	public $timestamps = false;

	protected $casts = [
		'franqueado_id' => 'int',
		'regiao_id' => 'int',
		'usuario_sistema_admin_id' => 'int'
	];

	protected $dates = [
		'data_inicio_atividade',
		'data_fim_atividade',
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'status',
		'franqueado_id',
		'regiao_id',
		'usuario_sistema_admin_id',
		'data_inicio_atividade',
		'data_fim_atividade',
		'data_cadastro',
		'data_atualizacao'
	];

	public function franqueado()
	{
		return $this->belongsTo(Franqueado::class);
	}

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
