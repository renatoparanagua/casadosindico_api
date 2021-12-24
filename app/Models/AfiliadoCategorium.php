<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class AfiliadoCategorium
 * 
 * @property int $id
 * @property int $afiliado_id
 * @property int $categoria_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Afiliado $afiliado
 * @property Categorium $categorium
 *
 * @package App\Models
 */
class AfiliadoCategorium extends Model
{
	use SoftDeletes;
	protected $table = 'afiliado_categoria';
	public $timestamps = false;

	protected $casts = [
		'afiliado_id' => 'int',
		'categoria_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'afiliado_id',
		'categoria_id',
		'data_cadastro',
		'data_atualizacao'
	];

	public function afiliado()
	{
		return $this->belongsTo(Afiliado::class);
	}

	public function categorium()
	{
		return $this->belongsTo(Categorium::class, 'categoria_id');
	}
}
