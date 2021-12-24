<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Sessaousuario
 * 
 * @property int $id
 * @property int|null $idUsuario
 * @property Carbon|null $inicioSessao
 * @property Carbon|null $fimSessao
 *
 * @package App\Models
 */
class Sessaousuario extends Model
{
	protected $table = 'sessaousuario';
	public $timestamps = false;

	protected $casts = [
		'idUsuario' => 'int'
	];

	protected $dates = [
		'inicioSessao',
		'fimSessao'
	];

	protected $fillable = [
		'idUsuario',
		'inicioSessao',
		'fimSessao'
	];
}
