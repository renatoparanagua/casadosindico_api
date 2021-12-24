<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AfiliadoFranqueadoAsaas extends Model
{
	use SoftDeletes;
	protected $table = 'afiliado_franqueado_asaas';
	public $timestamps = false;

	protected $casts = [];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [];
}
