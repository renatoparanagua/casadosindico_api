<?php

namespace App\Models\BO;

use App\Models\Sindico;
use App\Models\UsuarioApp;
use App\Util\Validacao;

class UsuarioAppBO
{
    public static function validarUsuarioAppLogin(UsuarioApp $usuario_app)
    {
        $validacao = new Validacao();
        $validacao->obrigatorio("email", $usuario_app->email, "E-mail");
        $validacao->obrigatorio("senha", $usuario_app->senha, "Senha");

        $validacao->tamanho_string("senha", $usuario_app->senha, 6, 255, "Senha");
        $validacao->email("email", $usuario_app->email, "E-mail");
        return $validacao;
    }

    public static function validarUsuarioAppSoft(UsuarioApp $usuario_app)
    {
        $validacao = new Validacao();
        $validacao->obrigatorio("email", $usuario_app->email, "E-mail");
        $validacao->obrigatorio("senha", $usuario_app->senha, "Senha");
        $validacao->obrigatorio("tipo", $usuario_app->tipo, "Tipo de usuário");

        $validacao->tamanho_string("senha", $usuario_app->senha, 6, 255, "Senha");
        $validacao->email("email", $usuario_app->email, "E-mail");
        return $validacao;
    }

    public static function validarUsuarioApp(UsuarioApp $usuario_app)
    {
        $validacao = new Validacao();
        $validacao->obrigatorio("nome", $usuario_app->nome, "Nome");
        $validacao->obrigatorio("email", $usuario_app->email, "E-mail");
        $validacao->obrigatorio("tipo", $usuario_app->email, "Tipo de usuário");
        $validacao->obrigatorio("senha", $usuario_app->senha, "Senha");
        $validacao->tamanho_string("senha", $usuario_app->senha, 6, 255, "Senha");
        $validacao->email("email", $usuario_app->email, "E-mail");
        return $validacao;
    }
}
