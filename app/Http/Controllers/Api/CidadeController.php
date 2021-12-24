<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Cidade as ResourcesCidade;
use App\Models\Bairro;
use App\Models\Cidade;
use Exception;
use Illuminate\Http\Request;

class CidadeController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request, null, new Cidade());
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
            $cidade = new Cidade();
            $cidade->nome = $request['nome'];
            $cidade->chave = $request['chave'];
            $cidade->estado_id = $request['estado_id'];
            $cidade->save();
            $data = new ResourcesCidade($cidade);
            return $this->successResponse('Cidade created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function estado($uf)
    {
        try {
            $cidades = Cidade::join('estado', 'estado.id', 'cidade.estado_id')
                ->where('estado.uf', $uf)->select('cidade.*')->get();
            return $this->successResponse('Success', $cidades);
        } catch (Exception $e) {
            return $this->errorResponse('Error');
        }
    }

    public function regiao($regiao_id)
    {
        try {
            $cidades = Bairro::join('cidade', 'cidade.id', 'bairro.cidade_id')
                ->where('bairro.regiao_id', $regiao_id)->select('cidade.*')->get();
            return $this->successResponse('Success', $cidades);
        } catch (Exception $e) {
            return $this->errorResponse('Error');
        }
    }
}
