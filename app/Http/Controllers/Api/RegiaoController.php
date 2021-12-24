<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Regiao as ResourcesRegiao;
use App\Models\Bairro;
use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\Regiao;
use App\Util\Util;
use Exception;
use Illuminate\Http\Request;

class RegiaoController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request, null, new Regiao());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $regioesAux = Regiao::where("id", "<>", 1)->where("id", "<>", 12)->get();

            $regioes = [];
            foreach ($regioesAux as $regiao) {
                $regiaoFranqueado = FranqueadoRegiao::where("regiao_id", $regiao->id)->where("status", "ativo")->orderBy("id", "desc")->first();
                if ($regiaoFranqueado) {
                    $franqueado = Franqueado::where("id", $regiaoFranqueado->franqueado_id)->first();
                    if ($franqueado) {
                        $token = Util::getTokenAsaasFranqueadoById($franqueado->id);
                        if ($token != null) {
                            $regioes[] = $regiao;
                        }
                    }
                }
            }

            $data = new ResourcesRegiao($regioes);
            return $this->successResponse('Success!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing you request');
        }
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
            $regiao = new Regiao();
            $regiao->nome = $request['nome'];
            $regiao->descricao = $request['descricao'];
            $regiao->save();
            $data = new ResourcesRegiao($regiao);
            return $this->successResponse('Regiao created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing you request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Regiao  $regiao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $regiao = Regiao::findOrFail($id);
            $regiao->nome = $request['nome'];
            $regiao->descricao = $request['descricao'];
            $regiao->update();
            $data = new ResourcesRegiao($regiao);
            return $this->successResponse('Regiao created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing you request');
        }
    }

    public function estado($uf)
    {
        try {
            $regioes = Bairro::join('cidade', 'cidade.id', 'bairro.cidade_id')
                ->join('estado', 'estado.id', 'cidade.estado_id')
                ->join('regiao', 'regiao.id', 'bairro.regiao_id')
                ->select('regiao.*')
                ->where('estado.uf', $uf)->get();
            return $this->successResponse('Success', $regioes);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function cidade($cidade)
    {
        try {
            $regioes = Bairro::join('cidade', 'cidade.id', 'bairro.cidade_id')
                ->join('regiao', 'regiao.id', 'bairro.regiao_id')
                ->select('regiao.*')
                ->where('cidade.nome', 'like', '%' . $cidade . '%')
                ->get();
            return $this->successResponse('Success', $regioes);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
