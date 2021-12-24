<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AfiliadoOrcamentoInteresse
 * 
 * @property int $id
 * @property bool $interessado
 * @property bool $nao_interessante
 * @property bool $descartado
 * @property int $afiliado_id
 * @property int $orcamento_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Afiliado $afiliado
 * @property Orcamento $orcamento
 *
 * @package App\Models
 */
class AfiliadoOrcamentoInteresse extends Model
{
	use SoftDeletes;
	protected $table = 'afiliado_orcamento_interesse';
	public $timestamps = false;

	protected $casts = [
		'interessado' => 'bool',
		'nao_interessante' => 'int',
		'descartado' => 'int',
		'afiliado_id' => 'int',
		'orcamento_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'interessado',
		'nao_interessante',
		'afiliado_id',
		'orcamento_id',
		'data_cadastro',
		'data_atualizacao',
		'descartado'
	];

	public function afiliado()
	{
		return $this->belongsTo(Afiliado::class);
	}

	public function orcamento()
	{
		return $this->belongsTo(Orcamento::class);
	}
}
