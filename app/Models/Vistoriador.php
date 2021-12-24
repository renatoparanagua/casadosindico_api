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
 * Class Vistoriador
 * 
 * @property int $id
 * @property string $nome
 * @property int $usuario_app_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * @property int|null $franqueado_id
 * 
 * @property Franqueado $franqueado
 * @property UsuarioApp $usuario_app
 * @property Collection|Vistorium[] $vistoria
 *
 * @package App\Models
 */
class Vistoriador extends Model
{
	use SoftDeletes;
	protected $table = 'vistoriador';
	public $timestamps = false;

	protected $casts = [
		'usuario_app_id' => 'int',
		'franqueado_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'usuario_app_id',
		'data_cadastro',
		'data_atualizacao',
		'franqueado_id'
	];

	public function franqueado()
	{
		return $this->belongsTo(Franqueado::class);
	}

	public function usuario_app()
	{
		return $this->belongsTo(UsuarioApp::class);
	}

	public function vistoria()
	{
		return $this->hasMany(Vistorium::class);
	}
}
