<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Bairro as ResourcesBairro;
use App\Models\Bairro;
use App\Models\Cidade;
use App\Models\Regiao;
use App\Models\RegiaoFaixaCep;
use Exception;
use Illuminate\Http\Request;

class BairroController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request, null, new Bairro());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $bairro = new Bairro();
            $bairro->nome = $request['nome'];
            $bairro->chave = $request['chave'];
            $bairro->cidade_id = $request['cidade_id'];
            $bairro->save();
            $data = new ResourcesBairro($bairro);
            return $this->successResponse('Bairro updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function cidade($id)
    {
        try {
            $bairros = Bairro::join('cidade', 'cidade.id', 'bairro.cidade_id')
                ->where('cidade.id', $id)->select('bairro.*')->get();
            return $this->successResponse('Success', $bairros);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse('Error');
        }
    }

    public function regiao($regiao_id)
    {
        try {
            $cidades = [];

            $faixaRegiao = RegiaoFaixaCep::where("regiao_id", $regiao_id)->get();
            foreach ($faixaRegiao as $faixa) {
                $cidades[] = Cidade::where("id", $faixa->cidade_id)->first();
            }


            $bairros = Bairro::where("regiao_id", $regiao_id)->get();
            foreach ($bairros as $i => $bairro) {
                $addCidade = true;
                foreach ($cidades as $cidade) {
                    if ($cidade->id == $bairro->cidade->id) {
                        $addCidade = false;
                    }
                }
                if ($addCidade) {
                    $bairro->cidade->nome = $bairro->cidade->nome . " - " . $bairro->nome;
                    $cidades[] = $bairro->cidade;
                }
            }

            usort($cidades, function ($a, $b) {
                return $a['nome'] <=> $b['nome'];
            });

            return $this->successResponse('Success', $cidades);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse('Error');
        }
    }
}
