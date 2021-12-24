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
 * Class Condominio
 * 
 * @property int $id
 * @property string $nome
 * @property string $cep
 * @property string $bairro
 * @property string $endereco
 * @property string $numero
 * @property string $complemento
 * @property int $sindico_id
 * @property int $bairro_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Sindico $sindico
 * @property Collection|Orcamento[] $orcamentos
 *
 * @package App\Models
 */
class Condominio extends Model
{
	use SoftDeletes;
	protected $table = 'condominio';
	public $timestamps = false;

	protected $casts = [
		'sindico_id' => 'int',
		'bairro_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'cep',
		'bairro',
		'endereco',
		'numero',
		'complemento',
		'sindico_id',
		'bairro_id',
		'data_cadastro',
		'data_atualizacao'
	];

	public function sindico()
	{
		return $this->belongsTo(Sindico::class);
	}

	public function bairro()
	{
		return $this->belongsTo(Bairro::class);
	}

	public function orcamentos()
	{
		return $this->hasMany(Orcamento::class);
	}
}
