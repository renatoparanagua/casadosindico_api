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
 * Class PlanoAssinaturaAfiliadoRegiao
 * 
 * @property int $id
 * @property string $nome
 * @property string|null $descricao
 * @property float $valor
 * @property float $valor_comissao
 * @property int $statusPlano
 * @property int $quantidade_meses_vigencia
 * @property int $dias_trial
 * @property int $franqueado_regiao_plano_disponibilizado_id
 * @property Carbon|null $data_pagamento
 * @property Carbon|null $data_cancelamento
 * @property Carbon|null $data_expiracao
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property FranqueadoRegiaoPlanoDisponibilizado $franqueado_regiao_plano_disponibilizado
 * @property Collection|AfiliadoRegiao[] $afiliado_regiaos
 *
 * @package App\Models
 */
class PlanoAssinaturaAfiliadoRegiao extends Model
{
	use SoftDeletes;
	protected $table = 'plano_assinatura_afiliado_regiao';
	public $timestamps = false;

	protected $casts = [
		'valor' => 'float',
		'valor_comissao' => 'float',
		'statusPlano' => 'int',
		'quantidade_meses_vigencia' => 'int',
		'dias_trial' => 'int',
		'franqueado_regiao_plano_disponibilizado_id' => 'int'
	];

	protected $dates = [
		'data_pagamento',
		'data_cancelamento',
		'data_expiracao',
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
		'franqueado_regiao_plano_disponibilizado_id',
		'data_pagamento',
		'data_cancelamento',
		'data_expiracao',
		'data_cadastro',
		'data_atualizacao'
	];

	public function franqueado_regiao_plano_disponibilizado()
	{
		return $this->belongsTo(FranqueadoRegiaoPlanoDisponibilizado::class);
	}

	public function afiliado_regiaos()
	{
		return $this->hasMany(AfiliadoRegiao::class);
	}
}
