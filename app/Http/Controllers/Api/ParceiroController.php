<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Parceiro as ResourcesParceiro;
use App\Models\Parceiro;
use Exception;
use Illuminate\Http\Request;

class ParceiroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $parceiros = Parceiro::all();
            $data = new ResourcesParceiro($parceiros);
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
            $parceiro = new Parceiro();
            $parceiro->nome = $request['nome'];
            $parceiro->email = $request['email'];
            $parceiro->telefone = $request['telefone'];
            $parceiro->logo = $request['logo'];
            $parceiro->link = $request['link'];
            $parceiro->nome_responsavel = $request['nome_responsavel'];
            $parceiro->status = $request['status'];
            $parceiro->save();
            $data = new ResourcesParceiro($parceiro);
            return $this->successResponse('Parceiro created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Parceiro  $parceiro
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        try {
            $parceiro = Parceiro::findOrFail($id);
            $data = new ResourcesParceiro($parceiro);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Parceiro  $parceiro
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $parceiro = Parceiro::findOrFail($id);
            $parceiro->nome = $request['nome'];
            $parceiro->email = $request['email'];
            $parceiro->telefone = $request['telefone'];
            $parceiro->logo = $request['logo'];
            $parceiro->link = $request['link'];
            $parceiro->nome_responsavel = $request['nome_responsavel'];
            $parceiro->status = $request['status'];
            $parceiro->update();
            $data = new ResourcesParceiro($parceiro);
            return $this->successResponse('Parceiro updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Parceiro  $parceiro
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        try {
            $parceiro = Parceiro::findOrFail($id);
            $parceiro->delete();
            $data = new ResourcesParceiro($parceiro);
            return $this->successResponse('Parceiro deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
