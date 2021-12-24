<?php

namespace App\Http\Controllers\Api;



//Import the PHPMailer class into the global namespace

//SMTP needs accurate times, and the PHP time zone MUST be set
//This should be done in your php.ini, but this is how to do it if you don't have access to that
require '../lib/vendor/autoload.php';
require '../lib/vendor/phpmailer/phpmailer/src/PHPMailer.php';

use PHPMailer\PHPMailer\PHPMailer;

class EnviarEmail
{
    private $mail;
    function __construct()
    {
        $this->mail = new PHPMailer();
        $this->mail->isSMTP();
        $this->mail->SMTPDebug = 0;
        $this->mail->Host = 'casadosindico.srv.br';
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;
        $this->mail->CharSet = "UTF-8";
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'sender@casadosindico.srv.br';
        $this->mail->Password = 'sB4h3E1Au#Gf';
        $this->mail->setFrom('contato@casadosindico.srv.br', 'Casa do Síndico');
        $this->mail->AddCustomHeader("List-Unsubscribe", "<mailto:adm@casadosindico.srv.br?subject=Cancelar assinatura>, <https://casadosindico.srv.br/contato>");
    }


    public function addCc($email, $nome)
    {
        $this->mail->addBCC(trim($email), $nome);
    }


    public function send($assunto, $corpo, $adress, $name_adress, $reply = 'contato@casadosindico.srv.br', $reply_name = 'Casa do Síndico')
    {

        $this->mail->addReplyTo(trim($reply), trim($reply_name));
        $this->mail->addAddress(trim($adress), trim($name_adress));
        $this->mail->Subject = $assunto;
        $this->mail->msgHTML($corpo);

        if (!$this->mail->send()) {
            return false;
        } else {
            return true;
        }
    }

    public function sendCc($assunto, $corpo, $reply = 'contato@casadosindico.srv.br', $reply_name = 'Casa do Síndico')
    {

        $this->mail->addReplyTo(trim($reply), trim($reply_name));
        $this->mail->addAddress("repositorio@departamentodati.com.br", "Notificando afiliados");
        if (getenv('APP_DEBUG') == false) {
            $this->mail->addAddress("contato@casadosindico.srv.br", "Notificando afiliados");
            $this->mail->addAddress("adm@casadosindico.srv.br", "Notificando afiliados");
        }
        $this->mail->Subject = $assunto;
        $this->mail->msgHTML($corpo);

        if (!$this->mail->send()) {
            return false;
        } else {
            return true;
        }
    }
}
