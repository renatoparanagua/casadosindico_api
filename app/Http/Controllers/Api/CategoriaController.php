<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Categoria as ResourcesCategoria;
use App\Models\AfiliadoCategorium;
use App\Models\Categoria;
use Exception;
use Illuminate\Http\Request;

class CategoriaController extends Controller
{

    private $storage_url = 'categoria/imagem';

    public function __construct(Request $request)
    {
        parent::__construct($request, null, new Categoria());
    }


    public function index()
    {
        $categorias = Categoria::orderBy("nome", "ASC")->where("status", 1)->get();
        foreach ($categorias as $i => $categoria) {
            $categorias[$i]['subcategorias'] = Categoria::where("categoria_pai_id", $categoria->id)->where("status", 1)->orderBy("nome", "ASC")->get();
        }
        return $this->successResponse('Sucesso!', $categorias);
    }

    public function indexAfiliado($afiliado_id)
    {
        try {
            $categorias = Categoria::orderBy("nome", "ASC")->where("status", 1)->get();
            foreach ($categorias as $i => $categoria) {
                $categorias[$i]['subcategorias'] = Categoria::where("categoria_pai_id", $categoria->id)->where("status", 1)->orderBy("nome", "ASC")->get();
            }
            return $this->successResponse('Sucesso!', $categorias);
        } catch (Exception $e) {
            return $this->errorResponse($e);
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
            $categoria = new Categoria();
            $categoria->nome = $request['nome'];
            $categoria->descricao = $request['descricao'];
            $categoria->chave_url = $request['chave_url'];
            if ($request->has('image')) {
                if ($request->file('image')->isValid()) {
                    $request['imagem'] = $request->image->store($this->storage_url);
                }
            }
            $categoria->imagem = $request['imagem'];
            $categoria->save();
            $data = new ResourcesCategoria([$categoria]);
            return $this->successResponse('Categoria created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $categoria = Categoria::findOrFail($id);
            $categoria->nome = $request['nome'];
            $categoria->descricao = $request['descricao'];
            $categoria->chave_url = $request['chave_url'];
            if ($request->has('image')) {
                if ($request->file('image')->isValid()) {
                    $request['imagem'] = $request->image->store($this->storage_url);
                }
            }
            $categoria->imagem = $request['imagem'];
            $categoria->update();
            $data = new ResourcesCategoria([$categoria]);
            return $this->successResponse('Categoria updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
