<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\ImagemOrcamento as ResourcesImagemOrcamento;
use App\Models\ImagemOrcamento;
use Exception;
use Illuminate\Http\Request;

class ImagemOrcamentoController extends Controller
{
    private $storage_url = 'orcamento/imagem';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $imagens_orcamento = ImagemOrcamento::all();
            $data = new ResourcesImagemOrcamento($imagens_orcamento);
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
            $imagem_orcamento = new ImagemOrcamento();
            $imagem_orcamento->descricao = $request['descricao'];
            if ($request->has('imagem')) {
                if ($request->file('imagem')->isValid()) {
                    $request['caminho_imagem'] = $request->imagem->store($this->storage_url);
                }
            }
            $imagem_orcamento->caminho_imagem = $request['caminho_imagem'];
            $imagem_orcamento->orcamento_id = $request['orcamento_id'];
            $imagem_orcamento->save();
            $data = new ResourcesImagemOrcamento($imagem_orcamento);
            return $this->successResponse('Imagem orcamento created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\ImagemOrcamento  $imagemOrcamento
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $imagem_orcamento = ImagemOrcamento::findOrFail($id);
            $imagem_orcamento->descricao = $request['descricao'];
            if ($request->has('imagem')) {
                if ($request->file('imagem')->isValid()) {
                    $request['caminho_imagem'] = $request->imagem->store($this->storage_url);
                }
            }
            $imagem_orcamento->caminho_imagem = $request['caminho_imagem'];
            $imagem_orcamento->orcamento_id = $request['orcamento_id'];
            $imagem_orcamento->update();
            $data = new ResourcesImagemOrcamento($imagem_orcamento);
            return $this->successResponse('Imagem orcamento updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
