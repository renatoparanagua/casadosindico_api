<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\LogSendinBlue as ResourcesLogSendinBlue;
use App\Models\LogSendinBlue;
use Exception;
use Illuminate\Http\Request;

class WebHookController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request, null);
    }

    public function webhoockautentique($id, Request $request)
    {
        try {
            $logsending = new Logsendinblue();
            $logsending->retorno = $id;
            $logsending->save();
            return $this->successResponse('Log sendin blue deleted!', $logsending);
        } catch (Exception $e) {
            dd($e);
        }
    }
}
