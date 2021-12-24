<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Logerroemail
 * 
 * @property int $id
 * @property Carbon|null $dataErro
 * @property string|null $mensagemErro
 *
 * @package App\Models
 */
class Logerroemail extends Model
{
	protected $table = 'logerroemail';
	public $timestamps = false;

	protected $dates = [
		'dataErro'
	];

	protected $fillable = [
		'dataErro',
		'mensagemErro'
	];
}
