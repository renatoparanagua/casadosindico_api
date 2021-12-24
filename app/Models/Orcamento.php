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
 * Class Orcamento
 * 
 * @property int $id
 * @property string|null $nome
 * @property string|null $descricao
 * @property bool|null $status
 * @property bool|null $status_sindico
 * @property bool|null $status_afiliado
 * @property int $condominio_id
 * @property int $afiliado_id
 * @property int $categoria_id
 * @property int|null $regiao_id
 * @property Carbon $data_cadastro
 * @property Carbon $data_atualizacao
 * @property string|null $deleted_at
 * 
 * @property Afiliado $afiliado
 * @property Categorium $categorium
 * @property Condominio $condominio
 * @property Regiao $regiao
 * @property Collection|Afiliado[] $afiliados
 * @property Collection|ImagemOrcamento[] $imagem_orcamentos
 * @property Collection|Vistorium[] $vistoria
 *
 * @package App\Models
 */
class Orcamento extends Model
{
	use SoftDeletes;
	protected $table = 'orcamento';
	public $timestamps = false;

	protected $casts = [
		'status' => 'int',
		'status_sindico' => 'int',
		'status_afiliado' => 'int',
		'condominio_id' => 'int',
		'categoria_id' => 'int',
		'regiao_id' => 'int'
	];

	protected $dates = [
		'data_cadastro',
		'data_atualizacao'
	];

	protected $fillable = [
		'nome',
		'descricao',
		'status',
		'status_sindico',
		'status_afiliado',
		'condominio_id',
		'afiliado_id',
		'categoria_id',
		'regiao_id',
		'data_cadastro',
		'data_atualizacao'
	];

	public function afiliado()
	{
		return $this->belongsTo(Afiliado::class);
	}

	public function categoria()
	{
		return $this->belongsTo(Categoria::class, 'categoria_id');
	}

	public function condominio()
	{
		return $this->belongsTo(Condominio::class);
	}

	public function regiao()
	{
		return $this->belongsTo(Regiao::class);
	}

	public function afiliados()
	{
		return $this->belongsToMany(Afiliado::class, 'afiliado_orcamento_interesse')
			->withPivot('id', 'interessado', 'nao_interessante', 'data_cadastro', 'data_atualizacao', 'deleted_at');
	}

	public function imagem_orcamentos()
	{
		return $this->hasMany(ImagemOrcamento::class);
	}

	public function vistoria()
	{
		return $this->hasMany(Vistorium::class);
	}
}
