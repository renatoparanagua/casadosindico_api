<?php

namespace App\Http\Controllers\Api;

use App\Models\AfiliadoFranqueadoAsaas;
use App\Util\Formatacao;
use App\Util\ModusOperandiStatus;
use App\Util\Util;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Http;

class Asaas
{
    public static function getUrlAsaas()
    {
        $modus_operandi = Util::getModusOperandi();
        if ($modus_operandi == ModusOperandiStatus::$DEDUB) {
            return getenv("ASAAS_URL_DEBUG");
        } elseif ($modus_operandi == ModusOperandiStatus::$PRODUCAO) {
            return getenv("ASAAS_URL_PRODUCAO");
        }
    }

    public static function updateExternalNumberAssinatura($newExternalNumber, $id, $token)
    {
        $assinatura = (object) [
            "externalReference" => $newExternalNumber
        ];
        $response = Http::withHeaders([
            'access_token' => $token,
        ])->post(self::getUrlAsaas() . "/subscriptions/$id", [
            $assinatura
        ]);
        //$response->throw();
        return $response->json();
    }

    public static function criarAssinatura($dados, $token, $forma_pagamento = "BOLETO", $data_vencimento = null)
    {
        $diaHoje = date("d");
        if ($diaHoje <= 22) {
            $dataVencimento = date('Y-m-12', strtotime("+1 month", strtotime(date("Y-m-d"))));
        } elseif ($diaHoje >= 23) {
            $dataVencimento = date('Y-m-12', strtotime("+2 months", strtotime(date("Y-m-d"))));
        }

        $assinatura = (object) [
            "customer" => isset($dados->asaas_customer_id) ? $dados->asaas_customer_id : null,
            "billingType" => "BOLETO",
            "value" => $dados->valor,
            "nextDueDate" => $data_vencimento == null ? $dataVencimento : $data_vencimento,
            "description" => $dados->nome . " - " . $dados->descricao,
            "cycle" => isset($dados->ciclo) ? $dados->ciclo : "MONTHLY",
            "externalReference" => $dados->id,
            "fine" => ["value" => 2], //Multa
            "interest" => ["value" => 1], //Juros
            "discount" => [
                "value" => $dados->desconto,
                "dueDateLimitDays" => 0,
                "type" => "PERCENTAGE"
            ]
        ];
        $response = Http::withHeaders([
            'access_token' => $token,
        ])->post(self::getUrlAsaas() . "/subscriptions", [
            $assinatura
        ]);
        //$response->throw();
        $res = $response->json();
        $res['data_expiracao'] = $data_vencimento == null ? $dataVencimento : $data_vencimento;
        return $res;
    }


    public static function novoCustomer($afiliado, $token, $updateAfiliado = true, $franqueado_id = null)
    {

        $customer = (object) [
            "name" => $afiliado->razao_social,
            "email" => isset($afiliado->usuarioApp->email) ? $afiliado->usuarioApp->email : $afiliado->email,
            "phone" => $afiliado->telefone,
            "mobilePhone" => $afiliado->telefone,
            "cpfCnpj" => $afiliado->cnpj,
            "postalCode" => $afiliado->cep,
            "address" => $afiliado->rua,
            "addressNumber" => $afiliado->numero,
            "complement" => $afiliado->complemento,
            "province" => $afiliado->cidade,
            "externalReference" => $afiliado->id,
            "notificationDisabled" => false,
            "personType" => "JURIDICA",
            "additionalEmails" => isset($afiliado->usuarioApp->email) ? $afiliado->email : null,
            "municipalInscription" => $afiliado->inscricao_municipal,
            "stateInscription" => $afiliado->inscricao_estadual
        ];

        $response = Http::withHeaders([
            'access_token' => $token,
        ])->post(self::getUrlAsaas() . "/customers", [
            $customer
        ]);
        //$response->throw();
        if ($updateAfiliado) {
            $afiliadoAsaas = new AfiliadoFranqueadoAsaas();
            $afiliadoAsaas->afiliado_id = $afiliado->id;
            $afiliadoAsaas->franqueado_id = $franqueado_id;
            $afiliadoAsaas->asaas_customer_id = $response->json()['id'];
            $afiliadoAsaas->modo = Util::getModusOperandi();
            $afiliadoAsaas->save();
        }

        return $response->json();
    }



    public static function getCobrancasByStatus($customer_id, $status, $token)
    {
        $status = strtoupper($status);
        $response = Http::withHeaders([
            'access_token' => $token,
        ])->get(self::getUrlAsaas() . "/payments?status=$status&limit=100&customer=" . $customer_id);
        return $response->json();
    }


    public static function extractCobrancas($response)
    {
        return isset($response["data"]) ? $response["data"] : [];
    }


    public static function isPossuiCobrancaVencida($cobrancas, $diasVencidas = 10)
    {
        $possuiCobrancaVencida = false;
        if ($cobrancas) {
            foreach ($cobrancas as $cobranca) {
                if (Formatacao::diasPeriodo(is_array($cobranca) ? $cobranca['dueDate'] : $cobranca->dueDate, date("Y-m-d")) > $diasVencidas) {
                    $possuiCobrancaVencida = true;
                    break;
                }
            }
        }
        return $possuiCobrancaVencida;
    }


    public static function getCustomerByEmail($email, $token)
    {
        $response = Http::withHeaders([
            'access_token' => $token,
        ])->get(self::getUrlAsaas() . "/customers?email=$email");
        return $response->json();
    }


    public static function getCustomerByCNPJ($cnpj, $token)
    {
        $response = Http::withHeaders([
            'access_token' => $token,
        ])->get(self::getUrlAsaas() . "/customers?cpfCnpj=$cnpj");
        return $response->json();
    }



    public static function getAssinaturaById($id, $token)
    {
        $response = Http::withHeaders([
            'access_token' => $token,
        ])->get(self::getUrlAsaas() . "/subscriptions/$id");
        return $response->json();
    }


    public static function getAllAssinaturaByCustomer($customer_id, $token)
    {
        $response = Http::withHeaders([
            'access_token' => $token,
        ])->get(self::getUrlAsaas() . "/subscriptions?customer=$customer_id");
        return $response->json();
    }


    public static function getCobrancasByAssinatura($assinatura_id, $token)
    {
        $response = Http::withHeaders([
            'access_token' => $token,
        ])->get(self::getUrlAsaas() . "/subscriptions/$assinatura_id/payments");
        return $response->json();
    }

    public static function getCobrancasByAssinaturaByStatus($assinatura_id, $status, $token)
    {
        $status = strtoupper($status);
        $response = Http::withHeaders([
            'access_token' => $token,
        ])->get(self::getUrlAsaas() . "/subscriptions/$assinatura_id/payments?status=$status");
        return $response->json();
    }


    public static function cancelarAssinatura($assinatura_id, $token)
    {
        $response = Http::withHeaders([
            'access_token' => $token,
        ])->delete(self::getUrlAsaas() . "/subscriptions/$assinatura_id");
        return $response->json();
    }



    public static function getCustomer($dados, $token)
    {
    }

    public static function getAllCustomers($dados, $token)
    {
    }


    public static function getAll($id, $token)
    {
    }

    public static function getLabel($ciclo)
    {
        switch ($ciclo) {
            case 'MONTHLY':
                return "Mensal";
            case 'QUARTERLY':
                return "Trimestral";
            case 'SEMIANNUALLY':
                return "Semestral";
            case 'YEARLY':
                return "Anual";
        }
    }

    public static function getMesesCiclo($ciclo)
    {
        switch ($ciclo) {
            case 'MONTHLY':
                return 1;
            case 'QUARTERLY':
                return 3;
            case 'SEMIANNUALLY':
                return 6;
            case 'YEARLY':
                return 12;
        }
    }
}
