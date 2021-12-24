<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Politicas extends Model
{
	use SoftDeletes;
	protected $table = 'politica_privacidade';
	public $timestamps = false;

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [];
}
