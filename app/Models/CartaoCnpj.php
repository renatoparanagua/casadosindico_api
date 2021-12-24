<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class CartaoCnpj
 * 
 * @property int $id
 * @property string $status
 * @property string $arquivo
 * @property int $afiliado_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Afiliado $afiliado
 *
 * @package App\Models
 */
class CartaoCnpj extends Model
{
	use SoftDeletes;
	protected $table = 'cartao_cnpj';
	public $timestamps = false;

	protected $casts = [
		'afiliado_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'status',
		'arquivo',
		'afiliado_id',
		'data_cadastro',
		'data_atualizacao'
	];

	public function afiliado()
	{
		return $this->belongsTo(Afiliado::class);
	}
}
