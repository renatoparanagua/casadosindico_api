<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\UsuarioSistemaAdmin as ResourcesUsuarioSistemaAdmin;
use App\Models\UsuarioSistemaAdmin;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UsuarioSistemaAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $usuarios_sistema_admin = UsuarioSistemaAdmin::all();
            $data = new ResourcesUsuarioSistemaAdmin($usuarios_sistema_admin);
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
            $usuario_sistema_admin = new UsuarioSistemaAdmin();
            $usuario_sistema_admin->nome = $request['nome'];
            $usuario_sistema_admin->email = $request['email'];
            $usuario_sistema_admin->senha = Hash::make($request['senha']);
            $usuario_sistema_admin->tipo = $request['tipo'];
            $usuario_sistema_admin->status = $request['status'];
            $usuario_sistema_admin->save();
            $data = new ResourcesUsuarioSistemaAdmin($usuario_sistema_admin);
            return $this->successResponse('Usuario sistema admin created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\UsuarioSistemaAdmin  $usuarioSistemaAdmin
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        try {
            $usuario_sistema_admin = UsuarioSistemaAdmin::findOrFail($id);
            $data = new ResourcesUsuarioSistemaAdmin($usuario_sistema_admin);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\UsuarioSistemaAdmin  $usuarioSistemaAdmin
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $usuario_sistema_admin = UsuarioSistemaAdmin::findOrFail($id);
            $usuario_sistema_admin->nome = $request['nome'];
            $usuario_sistema_admin->email = $request['email'];
            $usuario_sistema_admin->senha = Hash::make($request['senha']);
            $usuario_sistema_admin->tipo = $request['tipo'];
            $usuario_sistema_admin->status = $request['status'];
            $usuario_sistema_admin->update();
            $data = new ResourcesUsuarioSistemaAdmin($usuario_sistema_admin);
            return $this->successResponse('Usuario sistema admin update!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\UsuarioSistemaAdmin  $usuarioSistemaAdmin
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        try {
            $usuario_sistema_admin = UsuarioSistemaAdmin::findOrFail($id);
            $usuario_sistema_admin->delete();
            $data = new ResourcesUsuarioSistemaAdmin($usuario_sistema_admin);
            return $this->successResponse('Usuario sistema admin deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
