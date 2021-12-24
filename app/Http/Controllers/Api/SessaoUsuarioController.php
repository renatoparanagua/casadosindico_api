<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\SessaoUsuario as ResourcesSessaoUsuario;
use App\Models\SessaoUsuario;
use Exception;
use Illuminate\Http\Request;

class SessaoUsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $sessoes_usuario = SessaoUsuario::all();
            $data = new ResourcesSessaoUsuario($sessoes_usuario);
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
            $sessao_usuario = new SessaoUsuario();
            $sessao_usuario->idUsuario = $request['idUsuario'];
            $sessao_usuario->inicioSessao = $request['inicioSessao'];
            $sessao_usuario->fimSessao = $request['fimSessao'];
            $sessao_usuario->save();
            $data = new ResourcesSessaoUsuario($sessao_usuario);
            return $this->successResponse('Sessao usuario created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\SessaoUsuario  $sessaoUsuario
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        try {
            $sessao_usuario = SessaoUsuario::findOrFail($id);
            $data = new ResourcesSessaoUsuario($sessao_usuario);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\SessaoUsuario  $sessaoUsuario
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $sessao_usuario = SessaoUsuario::findOrFail($id);
            $sessao_usuario->idUsuario = $request['idUsuario'];
            $sessao_usuario->inicioSessao = $request['inicioSessao'];
            $sessao_usuario->fimSessao = $request['fimSessao'];
            $sessao_usuario->update();
            $data = new ResourcesSessaoUsuario($sessao_usuario);
            return $this->successResponse('Sessao usuario updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\SessaoUsuario  $sessaoUsuario
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        try {
            $sessao_usuario = SessaoUsuario::findOrFail($id);
            $sessao_usuario->delete();
            $data = new ResourcesSessaoUsuario($sessao_usuario);
            return $this->successResponse('Sessao usuario deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
