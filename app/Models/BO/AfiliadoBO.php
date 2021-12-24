<?php

namespace App\Models\BO;

use App\Models\Afiliado;
use App\Util\Validacao;

class AfiliadoBO
{
    public static function validarAfiliadoSoft($afiliado)
    {
        $validacao = new Validacao();
        $validacao->obrigatorio("razao_social", $afiliado->razao_social, "Razão social");
        $validacao->obrigatorio("nome_fantasia", $afiliado->nome_fantasia, "Nome fantasia");
        $validacao->obrigatorio("cnpj", $afiliado->cnpj, "CNPJ");

        if ($afiliado->cnpj)
            $validacao->validarCnpjGeral("CNPJ", $afiliado->cnpj, "CNPJ");

        $validacao->email("email", $afiliado->email, "E-mail");
        $validacao->inteiro("numero-funcionarios", $afiliado->numero_funcionarios, "Número de funcionários");
        return $validacao;
    }

    public static function validarAfiliado(Afiliado $afiliado)
    {
        $validacao = new Validacao();
        $validacao->obrigatorio("razao_social", $afiliado->razao_social, "Razão social");
        $validacao->obrigatorio("nome_fantasia", $afiliado->nome_fantasia, "Nome fantasia");
        $validacao->obrigatorio("cnpj", $afiliado->cnpj, "CNPJ");
        $validacao->obrigatorio("cartao_cnpj", $afiliado->cartao_cnpj, "Cartão CNPJ");
        $validacao->obrigatorio("inscricao_municipal", $afiliado->inscricao_municipal, "Inscrição municipal");
        $validacao->obrigatorio("inscricao_estadual", $afiliado->inscricao_estadual, "Inscrição estadual");
        $validacao->obrigatorio("estado", $afiliado->estado, "Estado");
        $validacao->obrigatorio("cidade", $afiliado->cidade, "Cidade");
        $validacao->obrigatorio("bairro", $afiliado->bairro, "Bairro");
        $validacao->obrigatorio("numero", $afiliado->numero, "Número");
        $validacao->obrigatorio("rua", $afiliado->rua, "Rua");
        $validacao->obrigatorio("cep", $afiliado->cep, "CEP");
        $validacao->obrigatorio("cep", $afiliado->telefone, "Telefone");
        $validacao->obrigatorio("complemento", $afiliado->complemento, "Complemento");
        $validacao->obrigatorio("ramo_atividade", $afiliado->rumo_atividade, "Ramo de atividade");
        $validacao->obrigatorio("numero_funcionarios", $afiliado->numero_funcionarios, "Número de funcionários");

        if ($afiliado->cnpj)
            $validacao->validarCnpjGeral("CNPJ", $afiliado->cnpj, "CNPJ");

        $validacao->email("email", $afiliado->email, "E-mail");
        $validacao->inteiro("numero-funcionarios", $afiliado->numero_funcionarios, "Número de funcionários");
        return $validacao;
    }
}
