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
 * Class FranqueadoRegiaoPlanoDisponibilizado
 * 
 * @property int $id
 * @property int $franqueado_regiao_id
 * @property int $plano_disponivel_franqueado_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property FranqueadoRegiao $franqueado_regiao
 * @property PlanoDisponivelFranqueado $plano_disponivel_franqueado
 * @property Collection|PlanoAssinaturaAfiliadoRegiao[] $plano_assinatura_afiliado_regiaos
 *
 * @package App\Models
 */
class FranqueadoRegiaoPlanoDisponibilizado extends Model
{
	use SoftDeletes;
	protected $table = 'franqueado_regiao_plano_disponibilizado';
	public $timestamps = false;

	protected $casts = [
		'franqueado_regiao_id' => 'int',
		'plano_disponivel_franqueado_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'franqueado_regiao_id',
		'plano_disponivel_franqueado_id',
		'data_cadastro',
		'data_atualizacao'
	];

	public function franqueado_regiao()
	{
		return $this->belongsTo(FranqueadoRegiao::class);
	}

	public function plano_disponivel_franqueado()
	{
		return $this->belongsTo(PlanoDisponivelFranqueado::class);
	}

	public function plano_assinatura_afiliado_regiaos()
	{
		return $this->hasMany(PlanoAssinaturaAfiliadoRegiao::class);
	}
}
