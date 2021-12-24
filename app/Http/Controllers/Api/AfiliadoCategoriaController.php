<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\AfiliadoCategoria as ResourcesAfiliadoCategoria;
use App\Models\AfiliadoCategorium as AfiliadoCategoria;
use Exception;
use Illuminate\Http\Request;

class AfiliadoCategoriaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $afiliado_categorias = AfiliadoCategoria::all();
            $data = new ResourcesAfiliadoCategoria($afiliado_categorias);
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
            $afiliado_categoria = new AfiliadoCategoria();
            $afiliado_categoria->afiliado_id = $request['afiliado_id'];
            $afiliado_categoria->categoria_id = $request['categoria_id'];
            $afiliado_categoria->save();
            $data = new ResourcesAfiliadoCategoria($afiliado_categoria);
            return $this->successResponse('Afiliado categoria created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\AfiliadoCategoria  $afiliadoCategoria
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        try {
            $afiliado_categoria = AfiliadoCategoria::findOrFail($id);
            $data = new ResourcesAfiliadoCategoria($afiliado_categoria);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\AfiliadoCategoria  $afiliadoCategoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $afiliado_categoria = AfiliadoCategoria::findOrFail($id);
            $afiliado_categoria->afiliado_id = $request['afiliado_id'];
            $afiliado_categoria->categoria_id = $request['categoria_id'];
            $afiliado_categoria->update();
            $data = new ResourcesAfiliadoCategoria($afiliado_categoria);
            return $this->successResponse('Afiliado categoria updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\AfiliadoCategoria  $afiliadoCategoria
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        try {
            $afiliado_categoria = AfiliadoCategoria::findOrFail($id);
            $afiliado_categoria->delete();
            $data = new ResourcesAfiliadoCategoria($afiliado_categoria);
            return $this->successResponse('Afiliado categoria deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
