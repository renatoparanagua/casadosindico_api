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
 * Class Bairro
 * 
 * @property int $id
 * @property string $nome
 * @property string $chave
 * @property int $cidade_id
 * @property int|null $regiao_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Cidade $cidade
 * @property Regiao $regiao
 * @property Collection|Rua[] $ruas
 *
 * @package App\Models
 */
class Bairro extends Model
{
	use SoftDeletes;
	protected $table = 'bairro';
	public $timestamps = false;

	protected $casts = [
		'cidade_id' => 'int',
		'regiao_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'chave',
		'cidade_id',
		'regiao_id',
		'data_cadastro',
		'data_atualizacao'
	];

	public function cidade()
	{
		return $this->belongsTo(Cidade::class);
	}

	public function regiao()
	{
		return $this->belongsTo(Regiao::class);
	}

	public function ruas()
	{
		return $this->hasMany(Rua::class);
	}
}
