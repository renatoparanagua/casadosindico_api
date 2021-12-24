<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use App\Util\StatusPlano;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AfiliadoRegiao
 * 
 * @property int $id
 * @property int $afiliado_id
 * @property int $regiao_id
 * @property int $plano_assinatura_afiliado_regiao_id
 * @property Carbon|null $data_pagamento_plano
 * @property Carbon|null $data_expiracao_plano
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Afiliado $afiliado
 * @property PlanoAssinaturaAfiliadoRegiao $plano_assinatura_afiliado_regiao
 * @property Regiao $regiao
 *
 * @package App\Models
 */
class AfiliadoRegiao extends Model
{
	use SoftDeletes;
	protected $table = 'afiliado_regiao';
	public $timestamps = false;

	protected $casts = [
		'afiliado_id' => 'int',
		'regiao_id' => 'int',
		'plano_assinatura_afiliado_regiao_id' => 'int'
	];

	protected $dates = [
		'data_pagamento_plano',
		'data_expiracao_plano',
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'afiliado_id',
		'regiao_id',
		'plano_assinatura_afiliado_regiao_id',
		'data_pagamento_plano',
		'data_expiracao_plano',
		'data_cadastro',
		'data_atualizacao'
	];

	public function afiliado()
	{
		return $this->belongsTo(Afiliado::class);
	}

	public function plano_assinatura_afiliado_regiao()
	{
		return $this->belongsTo(PlanoAssinaturaAfiliadoRegiao::class);
	}

	public function regiao()
	{
		return $this->belongsTo(Regiao::class);
	}

	public static function tranform($contrato)
	{
		$contrato['regiao'] = $contrato->regiao()->withTrashed()->first();
		$contrato['plano_assinatura'] = PlanoAssinaturaAfiliadoRegiao::withTrashed()->where("id", $contrato->plano_assinatura_afiliado_regiao_id)->first();
		if (isset($contrato['plano_assinatura']->data_cancelamento) &&  $contrato['plano_assinatura']->data_cancelamento != null) {
			$planoAssinatura = PlanoAssinaturaAfiliadoRegiao::withTrashed()->where("id", $contrato['plano_assinatura']->id)->first();
			if ($planoAssinatura) {
				$planoAssinatura->statusPlano = StatusPlano::$CANCELADO;
				$planoAssinatura->data_expiracao = null;
				$planoAssinatura->update();
			}

			$contrato['plano_assinatura'] = PlanoAssinaturaAfiliadoRegiao::withTrashed()->where("id", $contrato->plano_assinatura_afiliado_regiao_id)->first();
		} else if (isset($contrato['plano_assinatura']->id)) {
		}
		$contrato['assinatura'] = ContratoAssinatura::withTrashed()->where("afiliado_id", $contrato->afiliado_id)->where("plano_assinatura_afiliado_regiao_id", $contrato->plano_assinatura_afiliado_regiao_id)->orderBy("id", "desc")->first();
		return $contrato;
	}
}
