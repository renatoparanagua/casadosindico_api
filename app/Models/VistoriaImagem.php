<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class VistoriaImagem
 * 
 * @property int $id
 * @property string|null $descricao
 * @property string $caminho_imagem
 * @property int $vistoria_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Vistorium $vistorium
 *
 * @package App\Models
 */
class VistoriaImagem extends Model
{
	use SoftDeletes;
	protected $table = 'vistoria_imagem';
	public $timestamps = false;

	protected $casts = [
		'vistoria_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'descricao',
		'caminho_imagem',
		'vistoria_id',
		'data_cadastro',
		'data_atualizacao'
	];

	public function vistorium()
	{
		return $this->belongsTo(Vistorium::class, 'vistoria_id');
	}
}
