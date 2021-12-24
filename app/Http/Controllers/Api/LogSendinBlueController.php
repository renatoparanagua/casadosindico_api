<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\LogSendinBlue as ResourcesLogSendinBlue;
use App\Models\LogSendinBlue;
use Exception;
use Illuminate\Http\Request;

class LogSendinBlueController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request, null);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $logs_sendin_blue = LogSendinBlue::all();
            $data = new ResourcesLogSendinBlue($logs_sendin_blue);
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
            $log_sendin_blue = new LogSendinBlue();
            $log_sendin_blue->dataCriaocao = $request['dataCriaocao'];
            $log_sendin_blue->idOrcamento = $request['idOrcamento'];
            $log_sendin_blue->retorno = $request['retorno'];
            $log_sendin_blue->save();
            $data = new ResourcesLogSendinBlue($log_sendin_blue);
            return $this->successResponse('Log sendin blue created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\LogSendinBlue  $logSendinBlue
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        try {
            $log_sendin_blue = LogSendinBlue::findOrFail($id);
            $data = new ResourcesLogSendinBlue($log_sendin_blue);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\LogSendinBlue  $logSendinBlue
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $log_sendin_blue = LogSendinBlue::findOrFail($id);
            $log_sendin_blue->dataCriaocao = $request['dataCriaocao'];
            $log_sendin_blue->idOrcamento = $request['idOrcamento'];
            $log_sendin_blue->retorno = $request['retorno'];
            $log_sendin_blue->update();
            $data = new ResourcesLogSendinBlue($log_sendin_blue);
            return $this->successResponse('Log sendin blue updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\LogSendinBlue  $logSendinBlue
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        try {
            $log_sendin_blue = LogSendinBlue::findOrFail($id);
            $log_sendin_blue->delete();
            $data = new ResourcesLogSendinBlue($log_sendin_blue);
            return $this->successResponse('Log sendin blue deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function webhoockautentique($id, Request $request)
    {
        try {
            $logsending = new Logsendinblue();
            $logsending->retorno = $request->getAttr;
            $logsending->save();
            return $this->successResponse('Log sendin blue deleted!', $logsending);
        } catch (Exception $e) {
            dd($e);
        }
    }
}
