<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\FranqueadoRegiao as ResourcesFranqueadoRegiao;
use App\Models\FranqueadoRegiao;
use Exception;
use Illuminate\Http\Request;

class FranqueadoRegiaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $franqueado_regioes = FranqueadoRegiao::all();
            $data = new ResourcesFranqueadoRegiao($franqueado_regioes);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
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
            $franqueado_regiao = new FranqueadoRegiao();
            $franqueado_regiao->regiao_id = $request['regiao_id'];
            $franqueado_regiao->franqueado_id = $request['franqueado_id'];
            $franqueado_regiao->usuario_sistema_admin_id = $request['usuario_sistema_admin_id'];
            $franqueado_regiao->data_inicio_atividade = $request['data_inicio_atividade'];
            $franqueado_regiao->status = $request['status'];
            $franqueado_regiao->save();
            $data = new ResourcesFranqueadoRegiao($franqueado_regiao);
            return $this->successResponse('Franqueado regiao created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\FranqueadoRegiao  $franqueadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        try {
            $franqueado_regiao = FranqueadoRegiao::findOrFail($id);
            $data = new ResourcesFranqueadoRegiao($franqueado_regiao);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\FranqueadoRegiao  $franqueadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $franqueado_regiao = FranqueadoRegiao::findOrFail($id);
            $franqueado_regiao->regiao_id = $request['regiao_id'];
            $franqueado_regiao->franqueado_id = $request['franqueado_id'];
            $franqueado_regiao->usuario_sistema_admin_id = $request['usuario_sistema_admin_id'];
            $franqueado_regiao->data_inicio_atividade = $request['data_inicio_atividade'];
            $franqueado_regiao->status = $request['status'];
            $franqueado_regiao->update();
            $data = new ResourcesFranqueadoRegiao($franqueado_regiao);
            return $this->successResponse('Franqueado regiao updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\FranqueadoRegiao  $franqueadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        try {
            $franqueado_regiao = FranqueadoRegiao::findOrFail($id);
            $franqueado_regiao->delete();
            $data = new ResourcesFranqueadoRegiao($franqueado_regiao);
            return $this->successResponse('Franqueado regiao deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
