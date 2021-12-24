<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ImagemOrcamento
 * 
 * @property int $id
 * @property string|null $descricao
 * @property string $caminho_imagem
 * @property int $orcamento_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Orcamento $orcamento
 *
 * @package App\Models
 */
class ImagemOrcamento extends Model
{
	use SoftDeletes;
	protected $table = 'imagem_orcamento';
	public $timestamps = false;

	protected $casts = [
		'orcamento_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'descricao',
		'caminho_imagem',
		'orcamento_id',
		'data_cadastro',
		'data_atualizacao'
	];

	public function orcamento()
	{
		return $this->belongsTo(Orcamento::class);
	}
}
