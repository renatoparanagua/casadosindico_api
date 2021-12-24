<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use betterapp\LaravelDbEncrypter\Traits\EncryptableDbAttribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * Class Sindico
 * 
 * @property int $id
 * @property string $nome
 * @property string $CPF
 * @property int $numero_documento
 * @property string $telefone
 * @property int $usuario_app_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * @property int|null $franqueado_id
 * 
 * @property Franqueado $franqueado
 * @property UsuarioApp $usuario_app
 * @property Collection|Condominio[] $condominios
 *
 * @package App\Models
 */
class Sindico extends Model
{
	use SoftDeletes, EncryptableDbAttribute;
	protected $table = 'sindico';
	public $timestamps = false;

	/** 
	 * The attributes that should be encrypted/decrypted
	 * 
	 * @var array 
	 */
	protected $encryptable = [
		"nome",
		"CPF",
		"telefone",
		"numero_documento"
	];

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
		'CPF',
		'numero_documento',
		'telefone',
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

	public function condominios()
	{
		return $this->hasMany(Condominio::class);
	}
}
