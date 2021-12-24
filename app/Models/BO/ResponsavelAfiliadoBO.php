<?php

namespace App\Models\BO;

use App\Models\ResponsavelAfiliado;
use App\Util\Validacao;

class ResponsavelAfiliadoBO
{
    public static function validarResponsavelAfiliadoSoft($responsavel_afiliado)
    {
        $validacao = new Validacao();
        $validacao->obrigatorio("nome", $responsavel_afiliado->nome, "Nome");
        $validacao->obrigatorio("CPF", $responsavel_afiliado->CPF, "CPF");
        $validacao->obrigatorio("telefone", $responsavel_afiliado->telefone, "Telefone");
        $validacao->email("email", $responsavel_afiliado->email, "Email");
        $validacao->obrigatorio("cargo", $responsavel_afiliado->cargo, "Cargo");
        $validacao->inteiro("numero_documento", $responsavel_afiliado->numero_documento, "Número documento");

        return $validacao;
    }

    public static function validarResponsavelAfiliado(ResponsavelAfiliado $responsavel_afiliado)
    {
        $validacao = new Validacao();
        $validacao->obrigatorio("nome", $responsavel_afiliado->nome, "Nome");
        $validacao->obrigatorio("CPF", $responsavel_afiliado->CPF, "CPF");
        $validacao->obrigatorio("telefone", $responsavel_afiliado->telefone, "Telefone");
        $validacao->email("email", $responsavel_afiliado->email, "Email");
        $validacao->obrigatorio("cargo", $responsavel_afiliado->cargo, "Cargo");

        $validacao->validarCpfGeral("cpf", $responsavel_afiliado->CPF, "CPF");

        return $validacao;
    }
}
