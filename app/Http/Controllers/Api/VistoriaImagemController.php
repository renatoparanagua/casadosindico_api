<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\VistoriaImagem as ResourcesVistoriaImagem;
use App\Models\VistoriaImagem;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class VistoriaImagemController extends Controller
{
    private $storage_url = 'vistoria/imagem';
    public function __construct(Request $request)
    {
        parent::__construct($request, new VistoriaImagem());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $vistoria_imagens = VistoriaImagem::all();
            $data = new ResourcesVistoriaImagem($vistoria_imagens);
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
            $vistoria_imagem = new VistoriaImagem();
            $vistoria_imagem->descricao = $request['descricao'];
            if ($request->has('imagem')) {
                if ($request->file('imagem')->isValid()) {
                    $request['caminho_imagem'] = $request->imagem->store($this->storage_url);
                }
            }
            $vistoria_imagem->caminho_imagem = $request['caminho_imagem'];
            $vistoria_imagem->vistoria_id = $request['vistoria_id'];
            $vistoria_imagem->save();
            $data = new ResourcesVistoriaImagem($vistoria_imagem);
            return $this->successResponse('Vistoria imagem created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\VistoriaImagem  $vistoriaImagem
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $vistoria_imagem = VistoriaImagem::findOrFail($id);
            $vistoria_imagem->descricao = $request['descricao'];
            if ($request->has('imagem')) {
                if ($request->file('imagem')->isValid()) {
                    $request['caminho_imagem'] = $request->imagem->store($this->storage_url);
                }
            }
            $vistoria_imagem->caminho_imagem = $request['caminho_imagem'];
            $vistoria_imagem->vistoria_id = $request['vistoria_id'];
            $vistoria_imagem->update();
            $data = new ResourcesVistoriaImagem($vistoria_imagem);
            return $this->successResponse('Vistoria imagem updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
