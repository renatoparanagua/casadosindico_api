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
 * Class Categorium
 * 
 * @property int $id
 * @property string $nome
 * @property string $descricao
 * @property string $chave_url
 * @property string $imagem
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Collection|AfiliadoCategorium[] $afiliado_categoria
 * @property Collection|Orcamento[] $orcamentos
 *
 * @package App\Models
 */
class Categoria extends Model
{
	use SoftDeletes;
	protected $table = 'categoria';
	public $timestamps = false;

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'descricao',
		'chave_url',
		'imagem',
		'data_cadastro',
		'data_atualizacao'
	];

	public function afiliado_categoria()
	{
		return $this->hasMany(AfiliadoCategorium::class, 'categoria_id');
	}

	public function orcamentos()
	{
		return $this->hasMany(Orcamento::class, 'categoria_id');
	}
}
