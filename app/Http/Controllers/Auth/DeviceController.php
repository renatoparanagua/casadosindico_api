<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\UsuarioApp as ResourcesUsuarioApp;
use App\Models\Device;
use App\Models\UsuarioApp;
use Device as GlobalDevice;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DeviceController extends Controller
{

    public function __construct(Request $request)
    {
    }



    public function init(Request $request)
    {
        $this->newLog("DEVICE LOGIN");
        try {
            $device = Device::where("device_unique_id", $request['unique_id'])->first();
            if (!$device) {
                $device = new Device();
            }
            $device->ip = $request->getClientIp();
            $device->device_unique_id = $request['unique_id'];

            if ($device) {
                $device->tokens()->delete();
                $device->save();

                //Não salvar o remember token no banco de dados
                $device->remember_token = $device->createToken(md5($request['email']))->accessToken;
                $data = new ResourcesUsuarioApp($device);
                return $this->successResponse('Success', $data);
            } else {
                return $this->errorResponse('Unauthorized', 401);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function logout(Request $request)
    {
        try {
            $device = Device::where("device_unique_id", "abc123")->first();
            $device->tokens()->delete();
            $device->update();
            return $this->successResponse('User logged out!', null);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse('Error processing your request');
        }
    }
}
