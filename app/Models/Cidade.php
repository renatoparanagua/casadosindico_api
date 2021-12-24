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
 * Class Cidade
 * 
 * @property int $id
 * @property string $nome
 * @property string $chave
 * @property int $estado_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Estado $estado
 * @property Collection|Bairro[] $bairros
 *
 * @package App\Models
 */
class Cidade extends Model
{
	use SoftDeletes;
	protected $table = 'cidade';
	public $timestamps = false;

	protected $casts = [
		'estado_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'chave',
		'estado_id',
		'data_cadastro',
		'data_atualizacao'
	];

	public function estado()
	{
		return $this->belongsTo(Estado::class);
	}

	public function bairros()
	{
		return $this->hasMany(Bairro::class);
	}
}
