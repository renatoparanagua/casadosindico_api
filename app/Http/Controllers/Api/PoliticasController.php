<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Bairro as ResourcesBairro;
use App\Models\Bairro;
use App\Models\Cidade;
use App\Models\Politicas;
use App\Models\Regiao;
use App\Models\RegiaoFaixaCep;
use Exception;
use Illuminate\Http\Request;

class PoliticasController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request, null, new Politicas());
    }

    public function termosApp()
    {
        try {
            $termos = Politicas::orderBy("id", "desc")->first();
            return $this->successResponse('Success', $termos);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse('Error');
        }
    }
}
