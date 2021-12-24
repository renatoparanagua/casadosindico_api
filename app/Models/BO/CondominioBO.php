<?php

namespace App\Models\BO;

use App\Models\Condominio;
use App\Util\Validacao;

class CondominioBO
{

    public static function validarCondominio(Condominio $condominio)
    {
        $validacao = new Validacao();
        $validacao->obrigatorio("nome", $condominio->nome, "Nome");
        $validacao->obrigatorio("cep", $condominio->cep, "CEP");
        $validacao->obrigatorio("estado", $condominio->estado, "UF");
        $validacao->obrigatorio("cidade", $condominio->cidade, "Cidade");
        $validacao->obrigatorio("bairro", $condominio->bairro, "Bairro");
        $validacao->obrigatorio("endereco", $condominio->endereco, "Endereço");
        $validacao->tamanho_string('estado', $condominio->estado, 2, 2, 'UF');
        $validacao->obrigatorio("numero", $condominio->numero, "Número");
        $validacao->obrigatorio('cnpj', $condominio->cnpj, 'CNPJ');
        $validacao->validarCnpjGeral('cnpj', $condominio->cnpj, 'CNPJ');

        return $validacao;
    }
}
