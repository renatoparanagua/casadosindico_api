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

class AceiteTermos extends Model
{
	use SoftDeletes, EncryptableDbAttribute;
	protected $table = 'aceite_termos_politica';
	public $timestamps = false;

	protected $encryptable = [
		"ip"
	];
	protected $fillable = [
		'usuario_app_id',
		'ip',
		'id',
		'termos_politica_id'
	];
	protected $dates = [
		'data_cadastro'
	];


	public static function novoAceite($usuario_app_id, $termos_politica_id, $ip)
	{
		return AceiteTermos::create([
			"usuario_app_id" => $usuario_app_id,
			"termos_politica_id" => $termos_politica_id,
			"ip" => $ip
		]);
	}

	public static function termoAssinadoUsuario($usuario_app_id)
	{
		return AceiteTermos::where("usuario_app_id", $usuario_app_id)->orderBy("id", "desc")->first();
	}

	public static function lastTermo()
	{
		return Politicas::orderBy("id", "desc")->first();
	}
}
