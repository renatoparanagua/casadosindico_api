<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Rua
 * 
 * @property int $id
 * @property string $nome
 * @property string $cep
 * @property string $chave
 * @property int $bairro_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Bairro $bairro
 *
 * @package App\Models
 */
class Rua extends Model
{
	use SoftDeletes;
	protected $table = 'rua';
	public $timestamps = false;

	protected $casts = [
		'bairro_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'cep',
		'chave',
		'bairro_id',
		'data_cadastro',
		'data_atualizacao'
	];

	public function bairro()
	{
		return $this->belongsTo(Bairro::class);
	}
}
