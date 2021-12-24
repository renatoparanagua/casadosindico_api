<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\CartaoCnpj as ResourcesCartaoCnpj;
use App\Models\CartaoCnpj;
use Exception;
use Illuminate\Http\Request;

class CartaoCnpjController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $cartoes_cnpj = CartaoCnpj::all();
            $data = new ResourcesCartaoCnpj($cartoes_cnpj);
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
            $cartao_cnpj = new CartaoCnpj();
            $cartao_cnpj->arquivo = $request['arquivo'];
            $cartao_cnpj->afiliado_id = $request['afiliado_id'];
            $cartao_cnpj->status = $request['status'];
            $cartao_cnpj->save();
            $data = new ResourcesCartaoCnpj($cartao_cnpj);
            return $this->successResponse('Cartao cnpj deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\CartaoCnpj  $cartaoCnpj
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $cartao_cnpj = CartaoCnpj::findOrFail($id);
            $cartao_cnpj->arquivo = $request['arquivo'];
            $cartao_cnpj->afiliado_id = $request['afiliado_id'];
            $cartao_cnpj->status = $request['status'];
            $cartao_cnpj->update();
            $data = new ResourcesCartaoCnpj($cartao_cnpj);
            return $this->successResponse('Cartao cnpj deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
