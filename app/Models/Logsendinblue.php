<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Logsendinblue
 * 
 * @property int $id
 * @property Carbon|null $dataCriacao
 * @property int|null $idOrcamento
 * @property string|null $retorno
 *
 * @package App\Models
 */
class Logsendinblue extends Model
{
	protected $table = 'logsendinblue';
	public $timestamps = false;

	protected $casts = [
		'idOrcamento' => 'int'
	];

	protected $dates = [
		'dataCriacao'
	];

	protected $fillable = [
		'dataCriacao',
		'idOrcamento',
		'retorno'
	];
}
