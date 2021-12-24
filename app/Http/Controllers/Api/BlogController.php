<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Blog as ResourcesBlog;
use App\Models\Blog;
use Exception;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    private $storage_url = 'blog/imagem';
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $blogs = Blog::all();
            $data = new ResourcesBlog($blogs);
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
            $blog = new Blog();
            $blog->nome = $request['nome'];
            $blog->descricao = $request['descricao'];
            if ($request->has('imagem')) {
                if ($request->file('imagem')->isValid()) {
                    $request['imagem_principal'] = $request->imagem->store($this->storage_url);
                }
            }
            $blog->imagem_principal = $request['imagem_principal'];
            $blog->status = $request['status'];
            $blog->save();
            $data = new ResourcesBlog($blog);
            return $this->successResponse('Blog deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Blog  $blog
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $blog = Blog::findOrFail($id);
            $blog->nome = $request['nome'];
            $blog->descricao = $request['descricao'];
            if ($request->has('imagem')) {
                if ($request->file('imagem')->isValid()) {
                    $request['imagem_principal'] = $request->imagem->store($this->storage_url);
                }
            }
            $blog->imagem_principal = $request['imagem_principal'];
            $blog->status = $request['status'];
            $blog->update();
            $data = new ResourcesBlog($blog);
            return $this->successResponse('Blog deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
