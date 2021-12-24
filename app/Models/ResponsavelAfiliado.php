<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ResponsavelAfiliado
 * 
 * @property int $id
 * @property string $nome
 * @property string $email
 * @property int|null $numero_documento
 * @property string $CPF
 * @property string $telefone
 * @property string|null $cargo
 * @property int $afiliado_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Afiliado $afiliado
 *
 * @package App\Models
 */
class ResponsavelAfiliado extends Model
{
	use SoftDeletes;
	protected $table = 'responsavel_afiliado';
	public $timestamps = false;

	protected $casts = [
		'afiliado_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'email',
		'numero_documento',
		'CPF',
		'telefone',
		'cargo',
		'afiliado_id',
		'data_cadastro',
		'data_atualizacao'
	];

	public function afiliado()
	{
		return $this->belongsTo(Afiliado::class);
	}
}
