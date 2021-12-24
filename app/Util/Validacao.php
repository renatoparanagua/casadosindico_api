<?php

namespace App\Util;

class Validacao
{

    public $mensagem = array();

    public function email($vcampo, $vdado, $campo_message)
    {

        if ($vdado != "" && !is_null($vdado) && !filter_var($vdado, FILTER_VALIDATE_EMAIL)) {
            $this->mensagem[] = array("error_code" => "invalid-" . $vcampo, "error_message" => $campo_message . " inválido");
        }
    }

    public function inteiro($vcampo, $vdado, $campo_message)
    {
        if ($vdado === 0 || $vdado === "0") {
            return true;
        }

        if (!is_null($vdado) && !filter_var($vdado, FILTER_VALIDATE_INT)) {
            $this->mensagem[] = array("error_code" => "invalid-" . $vcampo, "error_message" => $campo_message . " aceita apenas dados inteiros (1; 2; 3; etc...)");
        }
    }

    public function real($vcampo, $vdado, $campo_message)
    {

        if (!is_null($vdado) && !filter_var($vdado, FILTER_VALIDATE_FLOAT)) {
            $this->mensagem[] = array("error_code" => "invalid-" . $vcampo, "error_message" => $campo_message . " aceita apenas dados numéricos reais (1,23; 1,10; 2; 5; etc...)");
        }
    }

    public function obrigatorio($vcampo, $vdado, $campo_message)
    {
        if (!($vdado === null) && $vdado === "") {
            $this->mensagem[] = array("error_code" => "required-" . $vcampo, "error_message" => $campo_message . " é obrigatório");
        }
    }

    public function validarCpfGeral($vcampo, $vdado, $campo_message)
    {
        if ($this->validarCPF($vdado) == false) {
            $this->mensagem[] = array("error_code" => "invalid-" . $vcampo, "error_message" => $campo_message . " é inválido");
        }
    }

    public function validarCnpjGeral($vcampo, $vdado, $campo_message)
    {
        if ($this->validarCNPJ($vdado) == false) {
            $this->mensagem[] = array("error_code" => "invalid-" . $vcampo, "error_message" => $campo_message . " é inválido");
        }
    }

    function validarCNPJ($cnpj)
    {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;

        $invalidos = [
            '00000000000000',
            '11111111111111',
            '22222222222222',
            '33333333333333',
            '44444444444444',
            '55555555555555',
            '66666666666666',
            '77777777777777',
            '88888888888888',
            '99999999999999'
        ];

        // Verifica se o CNPJ está na lista de inválidos
        if (in_array($cnpj, $invalidos)) {
            return false;
        }
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj[12] != ($resto < 2 ? 0 : 11 - $resto))
            return false;
        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj[$i] * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj[13] == ($resto < 2 ? 0 : 11 - $resto);
    }

    function validarCPF($cpf)
    {
        $cpf = str_replace('-', '', $cpf);
        $cpf = str_replace('.', '', $cpf);

        //$cpf = preg_replace("/[^0-9]/", "", (string) $cpf);

        if (
            $cpf == '00000000000' ||
            $cpf == '11111111111' ||
            $cpf == '22222222222' ||
            $cpf == '33333333333' ||
            $cpf == '44444444444' ||
            $cpf == '55555555555' ||
            $cpf == '66666666666' ||
            $cpf == '77777777777' ||
            $cpf == '88888888888' ||
            $cpf == '99999999999'
        ) {
            return false;
        }

        // Valida tamanho
        if (strlen($cpf) != 11)
            return false;

        // Calcula e confere primeiro dígito verificador
        for ($i = 0, $j = 10, $soma = 0; $i < 9; $i++, $j--)
            $soma += $cpf[$i] * $j;

        $resto = $soma % 11;

        if ($cpf[9] != ($resto < 2 ? 0 : 11 - $resto))
            return false;

        // Calcula e confere segundo dígito verificador
        for ($i = 0, $j = 11, $soma = 0; $i < 10; $i++, $j--)
            $soma += $cpf[$i] * $j;

        $resto = $soma % 11;

        return $cpf[10] == ($resto < 2 ? 0 : 11 - $resto);
    }

    function removerMascara($dados, $tipo)
    {
        if ($tipo == 'telefone') {
            $dados = str_replace('(', '', $dados);
            $dados = str_replace(')', '', $dados);
            $dados = str_replace('-', '', $dados);
            $dados = str_replace(' ', '', $dados);

            return $dados;
        }

        if ($tipo == 'valor') {
            $dados = str_replace('R$ ', '', $dados);
            $dados = str_replace('.', '', $dados);
            $dados = str_replace(',', '.', $dados);

            return $dados;
        }

        if ($tipo == 'cnpj') {
            $dados = str_replace('.', '', $dados);
            $dados = str_replace('/', '', $dados);
            $dados = str_replace('-', '', $dados);

            return $dados;
        }

        if ($tipo == 'cpf') {
            $dados = str_replace('.', '', $dados);
            $dados = str_replace('-', '', $dados);

            return $dados;
        }

        if ($tipo == 'cep') {
            $dados = str_replace('-', '', $dados);

            return $dados;
        }
    }

    public function tamanho_string($vcampo, $vdado, $min, $max, $campo_message)
    {
        if ($vdado != "" && !is_null($vdado) && !(strlen($vdado) >= $min && strlen($vdado) <= $max)) {
            if ($min == $max) {
                $this->mensagem[] = array("error_code" => "invalid-" . $vcampo, "error_message" => $campo_message . " deve conter " . $min . " caracteres");
            } else {
                $this->mensagem[] = array("error_code" => "invalid-" . $vcampo, "error_message" => $campo_message . " deve ter entre " . $min . " e " . $max . " caracteres");
            }
        }
    }

    public function addErro($vmsg)
    {

        $this->mensagem[] = $vmsg;
    }

    public function verifica()
    {

        if (empty($this->mensagem)) {
            return true;
        } else {
            return false;
        }
    }

    public function getErros()
    {

        if (!self::verifica()) {
            return $this->mensagem;
        } else {
            return false;
        }
    }

    public static function getError($messge, $code = "")
    {
        return array("error_code" => "invalid-" . $code, "error_message" => $messge);
    }
}
