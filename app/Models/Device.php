<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Notifications\Notifiable;

/**
 * Class Device
 * 
 * @property int $id
 * @property string $ip
 * @property string $device_unique_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Device extends Authenticatable
{
	use HasApiTokens, Notifiable, SoftDeletes;
	use SoftDeletes;
	protected $table = 'device';
	public $timestamps = false;

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'ip',
		'device_unique_id',
		'data_cadastro',
		'data_atualizacao'
	];
}
