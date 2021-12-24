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
 * Class Vistorium
 * 
 * @property int $id
 * @property string $descricao
 * @property Carbon $data_vistoria
 * @property Carbon|null $data_checkin
 * @property float|null $latitude_checkin
 * @property float|null $longitude_checkin
 * @property Carbon|null $data_checkout
 * @property float|null $latitude_checkout
 * @property float|null $longitude_checkout
 * @property int $vistoriador_id
 * @property int $orcamento_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Orcamento $orcamento
 * @property Vistoriador $vistoriador
 * @property Collection|VistoriaImagem[] $vistoria_imagems
 *
 * @package App\Models
 */
class Vistoria extends Model
{
	use SoftDeletes;
	protected $table = 'vistoria';
	public $timestamps = false;

	protected $casts = [
		'latitude_checkin' => 'float',
		'longitude_checkin' => 'float',
		'latitude_checkout' => 'float',
		'longitude_checkout' => 'float',
		'vistoriador_id' => 'int',
		'orcamento_id' => 'int'
	];

	protected $dates = [
		'data_vistoria',
		'data_checkin',
		'data_checkout',
		'data_atualizacao'
	];

	protected $fillable = [
		'descricao',
		'data_vistoria',
		'data_checkin',
		'latitude_checkin',
		'longitude_checkin',
		'data_checkout',
		'latitude_checkout',
		'longitude_checkout',
		'vistoriador_id',
		'orcamento_id',
		'data_cadastro',
		'data_atualizacao'
	];

	public function orcamento()
	{
		return $this->belongsTo(Orcamento::class);
	}

	public function vistoriador()
	{
		return $this->belongsTo(Vistoriador::class);
	}

	public function vistoria_imagems()
	{
		return $this->hasMany(VistoriaImagem::class, 'vistoria_id');
	}
}
