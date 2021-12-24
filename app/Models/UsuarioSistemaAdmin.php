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
 * Class UsuarioSistemaAdmin
 * 
 * @property int $id
 * @property string $nome
 * @property string $email
 * @property string $senha
 * @property int $status
 * @property string $tipo
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Collection|FranqueadoRegiao[] $franqueado_regiaos
 * @property Collection|PlanoDisponivelFranqueado[] $plano_disponivel_franqueados
 *
 * @package App\Models
 */
class UsuarioSistemaAdmin extends Authenticatable
{
	use HasApiTokens, Notifiable, SoftDeletes;
	protected $table = 'usuario_sistema_admin';
	public $timestamps = false;

	protected $casts = [
		'status' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'email',
		'senha',
		'status',
		'tipo',
		'data_cadastro',
		'data_atualizacao'
	];

	public function franqueado_regiaos()
	{
		return $this->hasMany(FranqueadoRegiao::class);
	}

	public function plano_disponivel_franqueados()
	{
		return $this->hasMany(PlanoDisponivelFranqueado::class);
	}
}
