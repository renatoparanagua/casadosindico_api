<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Parceiro as ResourcesParceiro;
use App\Models\Configuracao;
use App\Models\Parceiro;
use Exception;
use Illuminate\Http\Request;

class ConfiguracaoController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request, null, new Configuracao());
    }
    public function index()
    {
        $this->newLog("Listando configurações");
        try {
            $configuracao = Configuracao::orderBy("id", "DESC")->first();
            return $this->successResponse('Success', $configuracao);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
