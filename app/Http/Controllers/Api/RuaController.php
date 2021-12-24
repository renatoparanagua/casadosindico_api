<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Rua as ResourcesRua;
use App\Models\Rua;
use App\Util\Formatacao;
use Exception;
use Illuminate\Http\Request;

class RuaController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request, null, new Rua());
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
            $rua = new Rua();
            $rua->nome = $request['nome'];
            $rua->cep = $request['cep'];
            $rua->bairro_id = $request['bairro_id'];
            $rua->save();
            $data = new ResourcesRua($rua);
            return $this->successResponse('Rua created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }


    public function bairro($id)
    {
        try {
            $ruas = Rua::join('bairro', 'bairro.id', 'rua.bairro_id')
                ->where('bairro.id', $id)->select('rua.*')->get();
            return $this->successResponse('Success', $ruas);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse('Error');
        }
    }
    public function cidade($id)
    {
        try {
            $ruas = Rua::join('bairro', 'bairro.id', 'rua.bairro_id')
                ->join('cidade', 'cidade.id', 'bairro.cidade_id')
                ->where('cidade.id', $id)->select('rua.*')->get();
            return $this->successResponse('Success', $ruas);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse('Error');
        }
    }

    public function buscaPorCep($cep)
    {
        $cep = Formatacao::chave($cep);
        $url = "https://viacep.com.br/ws/$cep/json/";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        $result = curl_exec($ch);
        curl_close($ch);
        return $this->successResponse('Success', $result);
    }
}
