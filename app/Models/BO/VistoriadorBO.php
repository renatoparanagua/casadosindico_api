<?php

namespace App\Models\BO;

use App\Models\Vistoriador;
use App\Util\Validacao;

class VistoriadorBO
{
    public static function validarVistoriadorSoft($vistoriador)
    {
        $validacao = new Validacao();
        $validacao->obrigatorio("nome", $vistoriador->nome, "Nome");

        return $validacao;
    }

    public static function validarVistoriador(Vistoriador $vistoriador)
    {
        $validacao = new Validacao();
        $validacao->obrigatorio("nome", $vistoriador->nome, "Nome");

        return $validacao;
    }
}
