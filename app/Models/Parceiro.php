<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Parceiro
 * 
 * @property int $id
 * @property string|null $nome
 * @property string|null $logo
 * @property string|null $link
 * @property string|null $email
 * @property string|null $nome_responsavel
 * @property string|null $telefone
 * @property string $status
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 *
 * @package App\Models
 */
class Parceiro extends Model
{
	use SoftDeletes;
	protected $table = 'parceiros';
	public $timestamps = false;

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'logo',
		'link',
		'email',
		'nome_responsavel',
		'telefone',
		'status',
		'data_cadastro',
		'data_atualizacao'
	];
}
