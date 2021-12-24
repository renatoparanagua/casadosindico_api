<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Vistoriador as ResourcesVistoriador;
use App\Models\BO\VistoriadorBO;
use App\Models\Vistoriador;
use Exception;
use Illuminate\Http\Request;

class VistoriadorController extends Controller
{
    public function __construct(Request $request)
    {
        $class_name = new Vistoriador();
        parent::__construct($request, $class_name);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->newLog("Cadastro de vistoriador");
        try {
            $vistoriador = new Vistoriador();
            $vistoriador->nome = $request['nome'];
            $vistoriador->usuario_app_id = $this->usuario_logado->id;
            $validacao = VistoriadorBO::validarVistoriadorSoft($vistoriador);
            if ($validacao->verifica()) {
                $vistoriador->save();
                $data = new ResourcesVistoriador($vistoriador);
                return $this->successResponse('Vistoriador created!', $data);
            } else {
                return $this->errorResponse($validacao->getErros(), 403);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getCode());
            return $this->errorResponse('Error processing you request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Vistoriador  $vistoriador
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->newLog("Vistoriador atualizou  perfil");
        try {
            $vistoriador = $this->class_name::where($this->column_name, $this->usuario_logado->id)->findOrFail($this->usuario_tipo_id);
            $vistoriador->nome = $this->getValueRequest($request, $vistoriador, 'nome');
            $validacao = VistoriadorBO::validarVistoriador($vistoriador);
            if ($validacao->verifica()) {
                $vistoriador->update();
                $data = new ResourcesVistoriador($vistoriador);
                return $this->successResponse('Vistoriador atualizado', $data);
            } else {
                return $this->errorResponse($validacao->getErros(), 403);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getCode());
        }
    }

    public function partial_update(Request $request)
    {
        $this->newLog("Vistoriador atualizou  perfil");
        try {
            $vistoriador = $this->class_name::where($this->column_name, $this->usuario_logado->id)->findOrFail($this->usuario_tipo_id);
            $vistoriador->nome = $this->getValueRequest($request, $vistoriador, 'nome', true);
            $vistoriador->dados_acesso_condominio = $this->getValueRequest($request, $vistoriador, 'dados_acesso_condominio', true);
            $validacao = VistoriadorBO::validarVistoriador($vistoriador);
            if ($validacao->verifica()) {
                $vistoriador->update();
                $data = new ResourcesVistoriador($vistoriador);
                return $this->successResponse('Vistoriador atualizado', $data);
            } else {
                return $this->errorResponse($validacao->getErros(), 403);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getCode());
        }
    }
}
