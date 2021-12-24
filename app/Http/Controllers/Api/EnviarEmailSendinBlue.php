<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\EnviarEmail;
use Exception;
use Illuminate\Support\Facades\Http;



class EnviarEmailSendinBlue extends EnviarEmail
{
    private $api_url = "https://api.sendinblue.com/v3/";

    private $emailsCc = [];

    public function addCc($email, $nome)
    {

        $this->emailsCc[] = ["name" => $nome, "email" => trim($email)];
    }

    public function send($assunto, $corpo, $adress, $name_adress, $reply = 'contato@casadosindico.srv.br', $reply_name = 'Casa do Síndico')
    {
        $url = $this->api_url . 'smtp/email';
        try {
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "api-key" => "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",
                "Accept" => "application/json",
            ])->post($url, [
                "sender" => [
                    "name" => "$reply_name",
                    "email" => "$reply"
                ],
                "to" => [
                    [
                        "email" => "$adress",
                        "name" => "$name_adress"
                    ]
                ],
                "htmlContent" => "$corpo",
                "subject" => "$assunto",
                "replyTo" => ["email" => $reply, "name" => $reply_name],
                "headers" => [
                    "List-Unsubscribe" => "<mailto:adm@casadosindico.srv.br?subject=Cancelar assinatura>, <https://casadosindico.srv.br/contato>"
                ],
            ]);
            if (key_exists("messageId", $response->json())) {
                return true;
            } else {
                return false;
            }
            // return $response->json();
        } catch (Exception $e) {
            return ['errors' => $e->getMessage()];
        }
    }

    public function sendCc($assunto, $corpo, $reply = 'contato@casadosindico.srv.br', $reply_name = 'Casa do Síndico')
    {
        $url = $this->api_url . 'smtp/email';

        $to = [];
        $to[] = [
            "email" => "repositorio@departamentodati.com.br",
            "name" => "Notificando afiliados"
        ];
        if (getenv('APP_DEBUG') == false) {
            $to[] = [
                "email" => "contato@casadosindico.srv.br",
                "name" => "Notificando afiliados"
            ];
            $to[] = [
                "email" => "adm@casadosindico.srv.br",
                "name" => "Notificando afiliados"
            ];
        }

        try {
            $response = Http::withHeaders([
                "Content-Type" => "application/json",
                "api-key" => "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",
                "Accept" => "application/json",
            ])->post($url, [
                "sender" => [
                    "name" => "$reply_name",
                    "email" => "$reply"
                ],
                "to" => $to,
                "bcc" => $this->emailsCc,
                "htmlContent" => "$corpo",
                "subject" => "$assunto",
                "replyTo" => ["email" => $reply, "name" => $reply_name],
                "headers" => [
                    "List-Unsubscribe" => "<mailto:adm@casadosindico.srv.br?subject=Cancelar assinatura>, <https://casadosindico.srv.br/contato>"
                ],
            ]);
            if (key_exists("messageId", $response->json())) {
                return true;
            } else {
                return false;
            }
            // return $response->json();
        } catch (Exception $e) {
            return ['errors' => $e->getMessage()];
        }
    }
}
