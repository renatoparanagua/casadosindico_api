<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\FranqueadoRegiaoPlanoDisponibilizado as ResourcesFranqueadoRegiaoPlanoDisponibilizado;
use App\Models\FranqueadoRegiaoPlanoDisponibilizado;
use Exception;
use Illuminate\Http\Request;

class FranqueadoRegiaoPlanoDisponibilizadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $franqueado_regiao_planos_disponibilizados = FranqueadoRegiaoPlanoDisponibilizado::all();
            $data = new ResourcesFranqueadoRegiaoPlanoDisponibilizado($franqueado_regiao_planos_disponibilizados);
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
            $franqueado_regiao_plano_disponibilizado = new FranqueadoRegiaoPlanoDisponibilizado();
            $franqueado_regiao_plano_disponibilizado->franqueado_regiao_id = $request['franqueado_regiao_id'];
            $franqueado_regiao_plano_disponibilizado->plano_disponivel_franqueado_id = $request['plano_disponivel_franqueado_id'];
            $franqueado_regiao_plano_disponibilizado->save();
            $data = new ResourcesFranqueadoRegiaoPlanoDisponibilizado($franqueado_regiao_plano_disponibilizado);
            return $this->successResponse('Franqueado regiao plano disponibilizado created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\FranqueadoRegiaoPlanoDisponibilizado  $franqueadoRegiaoPlanoDisponibilizado
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        try {
            $franqueado_regiao_plano_disponibilizado = FranqueadoRegiaoPlanoDisponibilizado::findOrFail($id);
            $data = new ResourcesFranqueadoRegiaoPlanoDisponibilizado($franqueado_regiao_plano_disponibilizado);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\FranqueadoRegiaoPlanoDisponibilizado  $franqueadoRegiaoPlanoDisponibilizado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $franqueado_regiao_plano_disponibilizado = FranqueadoRegiaoPlanoDisponibilizado::findOrFail($id);
            $franqueado_regiao_plano_disponibilizado->franqueado_regiao_id = $request['franqueado_regiao_id'];
            $franqueado_regiao_plano_disponibilizado->plano_disponivel_franqueado_id = $request['plano_disponivel_franqueado_id'];
            $franqueado_regiao_plano_disponibilizado->updated();
            $data = new ResourcesFranqueadoRegiaoPlanoDisponibilizado($franqueado_regiao_plano_disponibilizado);
            return $this->successResponse('Franqueado regiao plano disponibilizado updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\FranqueadoRegiaoPlanoDisponibilizado  $franqueadoRegiaoPlanoDisponibilizado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        try {
            $franqueado_regiao_plano_disponibilizado = FranqueadoRegiaoPlanoDisponibilizado::findOrFail($id);
            $franqueado_regiao_plano_disponibilizado->delete();
            $data = new ResourcesFranqueadoRegiaoPlanoDisponibilizado($franqueado_regiao_plano_disponibilizado);
            return $this->successResponse('Franqueado regiao plano disponibilizado deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
