<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Usuario as ResourcesUsuario;
use App\Models\Usuario;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsuarioController extends Controller
{
    public function index()
    {
        $usuarios = Usuario::paginate(25);
        $data = new ResourcesUsuario([$usuarios]);
        return  $data;
    }
    public function show($id = null)
    {
        $usuario = Usuario::where("id",$this->usuario_logado->id)->first();
        return  $usuario;
    }
    public function store(Request $request)
    {
        try {
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }
            $data = $this->getData($request);
            $data['senha'] = Hash::make($data['senha']);
            $usuario = usuario::create($data);

            return $this->successResponse(
                'Usuario foi adicionado com sucesso.',
                $this->transform($usuario)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }
    public function update(Request $request, $id)
    {
        try {
            $validator = $this->getValidator($request);

            if ($validator->fails()) {
                return $this->errorResponse($validator->errors()->all());
            }

            $data = $this->getData($request);

            $usuario = Usuario::findOrFail($id);
            $usuario->update($data);

            return $this->successResponse(
                'Usuario foi atualizado com sucesso.',
                $this->transform($usuario)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }
    public function destroy($id = null)
    {
        try {
            $usuario = Usuario::findOrFail($id);
            $usuario->delete();

            return $this->successResponse(
                'Usuario foi deletado com sucesso.',
                $this->transform($usuario)
            );
        } catch (Exception $exception) {
            return $this->errorResponse('Unexpected error occurred while trying to process your request.');
        }
    }

    /**
     * Gets a new validator instance with the defined rules.
     *
     * @param Illuminate\Http\Request $request
     *
     * @return Illuminate\Support\Facades\Validator
     */
    protected function getValidator(Request $request)
    {
        $rules = [
            'nome' => 'required|string|min:1|max:45',
            'email' => 'required|string|min:1|max:45',
            'senha' => 'required|string|min:1|max:45',
            'saltSenha' => 'required',
            'staus' => 'required'
        ];

        return Validator::make($request->all(), $rules);
    }
    public function login(Request $request)
    {
        return 'Login page';
    }
}
