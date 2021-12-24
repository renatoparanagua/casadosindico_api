<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\LogErroEmail as ResourcesLogErroEmail;
use App\Models\LogErroEmail;
use Exception;
use Illuminate\Http\Request;

class LogErroEmailController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $logs_erro_email = LogErroEmail::all();
            $data = new ResourcesLogErroEmail($logs_erro_email);
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
            $log_erro_email = new LogErroEmail();
            $log_erro_email->dataErro = $request['dataErro'];
            $log_erro_email->mensagemErro = $request['mensagemErro'];
            $log_erro_email->save();
            $data = new ResourcesLogErroEmail($log_erro_email);
            return $this->successResponse('Log erro email created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\LogErroEmail  $logErroEmail
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        try {
            $log_erro_email = LogErroEmail::findOrFail($id);
            $data = new ResourcesLogErroEmail($log_erro_email);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\LogErroEmail  $logErroEmail
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $log_erro_email = LogErroEmail::findOrFail($id);
            $log_erro_email->dataErro = $request['dataErro'];
            $log_erro_email->mensagemErro = $request['mensagemErro'];
            $log_erro_email->update();
            $data = new ResourcesLogErroEmail($log_erro_email);
            return $this->successResponse('Log erro email updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\LogErroEmail  $logErroEmail
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        try {
            $log_erro_email = LogErroEmail::findOrFail($id);
            $log_erro_email->delete();
            $data = new ResourcesLogErroEmail($log_erro_email);
            return $this->successResponse('Log erro email deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
