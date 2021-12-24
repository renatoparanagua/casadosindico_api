<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\ContratoSocial as ResourcesContratoSocial;
use App\Models\ContratoSocial;
use Exception;
use Illuminate\Http\Request;

class ContratoSocialController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $contratos_sociais = ContratoSocial::all();
            $data = new ResourcesContratoSocial($contratos_sociais);
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
            $contrato_social = new ContratoSocial();
            $contrato_social->arquivo = $request['arquivo'];
            $contrato_social->afiliado_id = $request['afiliado_id'];
            $contrato_social->status = $request['status'];
            $contrato_social->save();
            $data = new ResourcesContratoSocial($contrato_social);
            return $this->successResponse('Conrato social created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\ContratoSocial  $contratoSocial
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        try {
            $contrato_social = ContratoSocial::findOrFail($id);
            $data = new ResourcesContratoSocial($contrato_social);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\ContratoSocial  $contratoSocial
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $contrato_social = ContratoSocial::findOrFail($id);
            $contrato_social->arquivo = $request['arquivo'];
            $contrato_social->afiliado_id = $request['afiliado_id'];
            $contrato_social->status = $request['status'];
            $contrato_social->update();
            $data = new ResourcesContratoSocial($contrato_social);
            return $this->successResponse('Contrato social updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\ContratoSocial  $contratoSocial
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        try {
            $contrato_social = ContratoSocial::findOrFail($id);
            $contrato_social->delete();
            $data = new ResourcesContratoSocial($contrato_social);
            return $this->successResponse('Contrato social deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
