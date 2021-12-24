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
 * Class Estado
 * 
 * @property int $id
 * @property string $nome
 * @property string $uf
 * @property string $chave
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Collection|Cidade[] $cidades
 *
 * @package App\Models
 */
class Estado extends Model
{
	use SoftDeletes;
	protected $table = 'estado';
	public $timestamps = false;

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'uf',
		'chave',
		'data_cadastro',
		'data_atualizacao'
	];

	public function cidades()
	{
		return $this->hasMany(Cidade::class);
	}
}
