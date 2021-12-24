<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Models\Cidade;
use App\Models\Estado;
use Illuminate\Http\Request;

class EstadoController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request, null, new Estado());
    }
}
