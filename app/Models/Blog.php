<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Blog
 * 
 * @property int $id
 * @property string $nome
 * @property string|null $imagem_principal
 * @property string|null $descricao
 * @property string $status
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Blog extends Model
{
	use SoftDeletes;
	protected $table = 'blog';
	public $timestamps = false;

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'imagem_principal',
		'descricao',
		'status',
		'data_cadastro',
		'data_atualizacao'
	];
}
