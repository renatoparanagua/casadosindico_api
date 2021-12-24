<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Api\Controller;
use App\Models\BO\UsuarioAppBO;
use App\Models\UsuarioApp;
use App\Util\Validacao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function __construct(Request $request)
    {
        #parent::__construct($request, null, new UsuarioApp());
    }

    public function login(Request $request)
    {
        $this->newLog("LOGIN " . $request['email']);
        try {


            $usuario_app = new UsuarioApp();
            $usuario_app->email = isset($request['email']) ? (string) $request['email'] : "";
            $usuario_app->senha = isset($request['senha']) ? (string) $request['senha'] : "";
            $validacao = UsuarioAppBO::validarUsuarioAppSoft($usuario_app);
            if (!$validacao->verifica()) {
                return $this->errorResponse($validacao->getErros(), 403);
            } else {
                $users = UsuarioApp::where('email', $request['email'])->get();
                $totalUsers = count($users);
                $autenticado = false;
                if ($totalUsers > 1) {
                    foreach ($users as $user) {
                        if ($user && (Hash::check($request['senha'], $user->senha) || $user->senha == md5($request['senha']) || $user->senha == $request['senha'])) {
                            if ($user->senha == md5($request['senha']) || $user->senha == $request['senha']) {
                                $user->senha = Hash::make($request['senha']);
                                $user->update();
                            }
                            $autenticado = true;
                            $senhaMaster = $user->senha;
                        }
                    }

                    if ($autenticado) {
                        foreach ($users as $i => $user) {
                            $user->senha = $senhaMaster;
                            $user->update();

                            $user->tokens()->delete(); //Invalida todos os tokens anteriores deste usuário
                            $user->update();

                            //Preparação para o retorno
                            $user->remember_token = $user->createToken(md5($request['email'] . $request['tipo']))->accessToken;
                            //unset($user->id); //Não retornar o id do usuario neste requisição
                            //unset($user->email); //Não retornar o email do usuario neste requisição
                            unset($user->senha); //Não retornar o hoash da senha do usuario neste requisição
                            $users[$i] = $user;
                        }
                        return $this->successResponse('Sessão iniciada.', $users);
                    } else {
                        return $this->errorResponse([Validacao::getError("Credenciais não conferem.", "login")], 403);
                    }
                } else if ($totalUsers == 1) {
                    $user = $users[0];
                } else if ($totalUsers == 0) {
                    $user = null;
                }






                if ($user && (Hash::check($request['senha'], $user->senha) || $user->senha == md5($request['senha']) || $user->senha == $request['senha'])) {
                    if ($user->senha == md5($request['senha']) || $user->senha == $request['senha']) {
                        $user->senha = Hash::make($request['senha']);
                    }
                    $user->tokens()->delete(); //Invalida todos os tokens anteriores deste usuário
                    $user->update();

                    //Preparação para o retorno
                    $user->remember_token = $user->createToken(md5($request['email']))->accessToken;
                    // unset($user->id); //Não retornar o id do usuario neste requisição
                    //unset($user->email); //Não retornar o email do usuario neste requisição
                    unset($user->senha); //Não retornar o hoash da senha do usuario neste requisição
                    return $this->successResponse('Sessão iniciada.', $user);
                } else {
                    return $this->errorResponse([Validacao::getError("Credenciais não conferem.", "login")], 403);
                }
            }
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function logout(Request $request)
    {
        $this->newLog("LOGOUT " . $this->usuario_logado->email);
        try {
            $this->usuario_logado = Auth::guard('api')->user();
            $user = UsuarioApp::where('id', $this->usuario_logado->id)->first();
            $user->token_notification = null;
            $user->tokens()->delete();
            $user->update();
            return $this->successResponse('Sessão encerrada.', null);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse('Error processing your request');
        }
    }
}
