<?php

namespace App\Models\BO;

use App\Models\Sindico;
use App\Util\Validacao;
use Illuminate\Http\Request;

class SindicoBO
{

    public static function transform($sindico)
    {
        return $sindico;
    }

    public static function validarSindicoSoft($sindico)
    {
        $validacao = new Validacao();
        $validacao->obrigatorio("nome", $sindico->nome, "Nome");
        $validacao->obrigatorio("numero_documento", $sindico->numero_documento, "Numero documento");
        $validacao->obrigatorio("CPF", $sindico->CPF, "CPF");
        $validacao->obrigatorio("telefone", $sindico->telefone, "Telefone");


        if ($sindico->CPF)
            $validacao->validarCpfGeral("CPF", $sindico->CPF, "CPF");

        return $validacao;
    }

    public static function validarSindico(Sindico $sindico)
    {
        $validacao = new Validacao();
        $validacao->obrigatorio("nome", $sindico->nome, "Nome");
        $validacao->obrigatorio("numero_documento", $sindico->numero_documento, "Numero documento");
        $validacao->obrigatorio("CPF", $sindico->CPF, "CPF");
        $validacao->obrigatorio("telefone", $sindico->telefone, "Telefone");
        if ($sindico->CPF)
            $validacao->validarCpfGeral("CPF", $sindico->CPF, "CPF");

        return $validacao;
    }

    public static function getDataOnlyName($request)
    {
        $rules = [
            'nome' => 'required|string|min:1|max:255'
        ];

        $data = $request->validate($rules);

        return $data;
    }

    public static function getData($request)
    {
        $rules = [
            'nome' => 'required|string|min:1|max:255'
        ];

        $data = $request->validate($rules);

        return $data;
    }
}
