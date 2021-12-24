<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Franqueado as ResourcesFranqueado;
use App\Models\Franqueado;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FranqueadoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $franqueados = Franqueado::all();
            $data = new ResourcesFranqueado($franqueados);
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
            $franqueado = new Franqueado();
            $franqueado->nome = $request['nome'];
            $franqueado->email = $request['email'];
            $franqueado->senha = Hash::make($request['senha']);
            $franqueado->cnpj = $request['cnpj'];
            $franqueado->cep = $request['cep'];
            $franqueado->estado = $request['estado'];
            $franqueado->cidade = $request['cidade'];
            $franqueado->bairro = $request['bairro'];
            $franqueado->rua = $request['rua'];
            $franqueado->inscricao_estadual = $request['inscricao_estadual'];
            $franqueado->inscricao_estadual = $request['inscricao_estadual'];
            $franqueado->inscricao_municipal = $request['inscricao_municipal'];
            $franqueado->cpf_responsavel = $request['cpf_responsavel'];
            $franqueado->rg_responsavel = $request['rg_responsavel'];
            $franqueado->telefone_responsavel = $request['telefone_responsavel'];
            $franqueado->profissao_responsavel = $request['profissao_responsavel'];
            $franqueado->token_assas_debug = $request['token_assas_debug'];
            $franqueado->token_assas_producao = $request['token_assas_producao'];
            $franqueado->save();
            $data = new ResourcesFranqueado($franqueado);
            return $this->successResponse('Franqueado created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Franqueado  $franqueado
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        try {
            $franqueado = Franqueado::findOrFail($id);
            $data = new ResourcesFranqueado($franqueado);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Franqueado  $franqueado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $franqueado = Franqueado::findOrFail($id);
            $franqueado->nome = $request['nome'];
            $franqueado->email = $request['email'];
            $franqueado->senha = Hash::make($request['senha']);
            $franqueado->cnpj = $request['cnpj'];
            $franqueado->cep = $request['cep'];
            $franqueado->estado = $request['estado'];
            $franqueado->cidade = $request['cidade'];
            $franqueado->bairro = $request['bairro'];
            $franqueado->rua = $request['rua'];
            $franqueado->inscricao_estadual = $request['inscricao_estadual'];
            $franqueado->inscricao_estadual = $request['inscricao_estadual'];
            $franqueado->inscricao_municipal = $request['inscricao_municipal'];
            $franqueado->cpf_responsavel = $request['cpf_responsavel'];
            $franqueado->rg_responsavel = $request['rg_responsavel'];
            $franqueado->telefone_responsavel = $request['telefone_responsavel'];
            $franqueado->profissao_responsavel = $request['profissao_responsavel'];
            $franqueado->token_assas_debug = $request['token_assas_debug'];
            $franqueado->token_assas_producao = $request['token_assas_producao'];
            $franqueado->update();
            $data = new ResourcesFranqueado($franqueado);
            return $this->successResponse('Franqueado updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Franqueado  $franqueado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        try {
            $franqueado = Franqueado::findOrFail($id);
            $franqueado->delete();
            $data = new ResourcesFranqueado($franqueado);
            return $this->successResponse('Franqueado deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
