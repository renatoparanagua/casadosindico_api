<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Contrato as ResourcesContrato;
use App\Models\Contrato;
use Exception;
use Illuminate\Http\Request;

class ContratoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $contratos = Contrato::all();
            $data = new ResourcesContrato($contratos);
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
            $contrato = new Contrato();
            $contrato->arquivo = $request['arquivo'];
            $contrato->afiliado_id = $request['afiliado_id'];
            $contrato->status = $request['status'];
            $contrato->save();
            $data = new ResourcesContrato($contrato);
            return $this->successResponse('Contrato created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        try {
            $contrato = Contrato::findOrFail($id);
            $data = new ResourcesContrato($contrato);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $contrato = Contrato::findOrFail($id);
            $contrato->arquivo = $request['arquivo'];
            $contrato->afiliado_id = $request['afiliado_id'];
            $contrato->status = $request['status'];
            $contrato->update();
            $data = new ResourcesContrato($contrato);
            return $this->successResponse('Contrato updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\Contrato  $contrato
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        try {
            $contrato = Contrato::findOrFail($id);
            $contrato->delete();
            $data = new ResourcesContrato($contrato);
            return $this->successResponse('Contrato deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
