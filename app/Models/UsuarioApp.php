<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Class UsuarioApp
 * 
 * @property int $id
 * @property string $email
 * @property string $senha
 * @property string $tipo
 * @property string|null $token_notification
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Collection|Afiliado[] $afiliados
 * @property Collection|Sindico[] $sindicos
 * @property Collection|Vistoriador[] $vistoriadors
 *
 * @package App\Models
 */
class UsuarioApp extends Authenticatable
{
	use HasApiTokens, Notifiable, SoftDeletes;
	protected $table = 'usuario_app';
	public $timestamps = false;

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'email',
		'senha',
		'tipo',
		'token_notification',
		'data_cadastro',
		'data_atualizacao'
	];

	public function afiliados()
	{
		return $this->hasMany(Afiliado::class);
	}

	public function sindicos()
	{
		return $this->hasMany(Sindico::class);
	}

	public function vistoriadors()
	{
		return $this->hasMany(Vistoriador::class);
	}
}
