<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\EnviarEmailSendinBlue;

use App\Models\Afiliado;
use App\Models\AfiliadoRegiao;
use App\Models\AfiliadoCategorium;
use App\Models\AfiliadoFranqueadoAsaas;
use App\Models\AfiliadoOrcamentoInteresse;
use App\Models\Condominio;
use App\Models\Configuracao;
use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\LogSystem;
use App\Models\Notificacao;
use App\Models\Orcamento;
use App\Models\PlanoAssinaturaAfiliadoRegiao;
use App\Models\Sindico;
use App\Models\UsuarioApp;
use App\Util\Formatacao;
use App\Util\StatusAsass;
use App\Util\StatusPlano;
use App\Util\Util;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SenderEmails
{
    public static function getConfig()
    {
        return Configuracao::orderBy("id", "DESC")->first();
    }

    public static function emailConfirmacao(Controller $context, Request $request, $tipo = "sendingblue")
    {
        $config = self::getConfig();

        $link_android = "";
        $link_ios = "";

        if ($config['link_android']) {
            $link_android = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config['link_android']}'>
                                <img alt=''src='https://casadosindico.srv.br/assets/playstore.png' />
                             </a>";
        }

        if ($config['link_ios']) {
            $link_ios = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config['link_ios']}'>
                            <img alt=''src='https://casadosindico.srv.br/assets/appstore.png' />
                            </a>";
        }

        $nome = $request['nome'];
        $email = $request['email'];

        if ($email != $context->usuario_logado->email) {
            return $context->successResponse('Esse e-mail não é o seu', null);
        }

        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h1>Seja bem-vindo a {$config->nome_empresa}</h1>
                                    <h2 style='text-align: center;'>Você está recebendo este e-mail para verificação da conta do aplicativo {$config->nome_empresa}</h2>
                                </div>
                                <p style='text-align: center;' align='center'>
                                    <h5>Por favor, clique no link para verificar sua conta de e-mail</h5>
                                    <a style='padding: 8px; border: 1px solid #ededed; font-size: 21px;' href='" . getenv("SITE_URL") . "/confirmarConta.php?q=" . md5(md5($context->usuario_logado->id)) . "'>Verificar e-mail</a>
                                </p>
                                <p style='text-align: center;'>
                                    <h3>Baixe o aplicativo em uma das lojas</h3>
                                    $link_android &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    $link_ios
                                    <p>
                                        Se você não lembrar da sua senha, baixe o aplicativo, clique em <b>Já possuo uma conta</b> e depois clique em <b>Esqueci minha senha</b>. Você receberá uma senha nova neste mesmo e-mail.
                                    </p>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($tipo == "sendingblue") {
                //Sendingblue
                $sender = new EnviarEmailSendinBlue();
            } else {
                //Server
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                "{$config->nome_empresa} - Confirme o seu e-mail",
                $html,
                $email,
                $nome
            );
            return $context->successResponse('E-mail enviado com sucesso!', $res);
        } catch (Exception $e) {
            return $context->successResponse('Erro!', $e);
        }
    }


    public static function novasenha($context, Request $request, $tipo = "sendingblue")
    {
        try {
            $config = self::getConfig();
            $email = $request['email'];
            $novaSenha = substr(md5(rand()), 0, 6);

            $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h2 style='text-align: center;'>Você solicitou uma nova senha para o App Casa do Síndico</h2>
                                </div>
                                <p>
                                    Sua nova senha é " . $novaSenha . "
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

            $usuario = UsuarioApp::where("email", $email)->first();


            if ($usuario) {

                $usuario->senha = Hash::make($novaSenha);
                $usuario->update();

                if ($tipo == "sendingblue") {
                    //Sendingblue
                    $sender = new EnviarEmailSendinBlue();
                } else {
                    //Server
                    $sender = new EnviarEmail();
                }
                $res = $sender->send(
                    "{$config->nome_empresa} - Sua nova senha",
                    $html,
                    $email,
                    $email
                );
                return $context->successResponse('E-mail enviado com sucesso!', $res);
            }
            return $context->errorResponse([array("error_code" => "exists-usuario", "error_message" => "Usuário não encontrado.")], 403);
        } catch (Exception $e) {
            return $context->errorResponse([array("error_code" => "exists-email", "error_message" => "E-mail não encontrado.")], 403);
        }
    }




    public static function novasenhaPerfil(Controller $context, Request $request, $tipo = "sendingblue")
    {
        $config = self::getConfig();

        try {
            $novaSenha = $request['senha'];
            $usuario = UsuarioApp::where("id", $context->usuario_logado->id)->first();


            if ($usuario) {
                $usuario->senha = Hash::make($novaSenha);
                $usuario->update();

                $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h2 style='text-align: center;'>Sua senha foi alterada pelo aplicativo.</h2>
                                </div>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

                if ($tipo == "sendingblue") {
                    //Sendingblue
                    $sender = new EnviarEmailSendinBlue();
                } else {
                    //Server
                    $sender = new EnviarEmail();
                }
                $res = $sender->send(
                    "{$config->nome_empresa} - Senha alterada",
                    $html,
                    $usuario->email,
                    $usuario->email
                );
                return $context->successResponse('Senha alterada com sucesso.', [$usuario, ["send" => $res]]);
            }
            return $context->errorResponse([array("error_code" => "exists-email", "error_message" => "E-mail não encontrado.")], 403);
        } catch (Exception $e) {
            return $context->errorResponse([array("error_code" => "exists-email", "error_message" => "Erro. " . $context->usuario_logado->id)], 403);
        }
    }

    public static function enviarEmailFranqueado($email, $nome, $id, $silicitadoPor = "App", $tipo = "sendingblue")
    {
        $config = self::getConfig();
        $aux = $email;

        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h2>Sua franquia acaba de receber uma nova solicitação pelo $silicitadoPor.</h2>
                                    <h1>Solicitação #$id</h1>
                                    <h2 style='text-align: center;'>Acesse o painel administrativo com o e-mail $aux e confira.</h2>
                                </div>
                                <p style='text-align: center;' align='center'>
                                    <h5>Acesse o painel administrativo</h5>
                                    <a style='padding: 8px; border: 1px solid #ededed; font-size: 21px;' href='https://admin2.casadosindico.srv.br/admin_franqueado'>Acessar o painel</a>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($tipo == "sendingblue") {
                //Sendingblue
                $sender = new EnviarEmailSendinBlue();
            } else {
                //Server
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                $config->nome_empresa . " - Nova solicitação pelo $silicitadoPor",
                $html,
                $email,
                $nome
            );
            return $res;
        } catch (Exception $e) {
            return false;
        }
    }




    public static function enviarEmailSuperAdmin($email, $nome_franquia, $id, $silicitadoPor = "App", $tipo = "sendingblue")
    {
        $config = self::getConfig();
        $aux = $email;

        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h2>A franquia {$nome_franquia} acaba de receber uma nova solicitação pelo $silicitadoPor.</h2>
                                    <h1>Solicitação #$id</h1>
                                    <h2 style='text-align: center;'>Acesse o painel administrativo com o e-mail $aux e acompanhe.</h2>
                                </div>
                                <p style='text-align: center;' align='center'>
                                    <h5>Acesse o painel administrativo</h5>
                                    <a style='padding: 8px; border: 1px solid #ededed; font-size: 21px;' href='https://admin2.casadosindico.srv.br/admin'>Acessar o painel</a>
                                </p>
                                <p>
                                    LOG: " . json_encode(Orcamento::where("id", $id)->first()) . "
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($tipo == "sendingblue") {
                //Sendingblue
                $sender = new EnviarEmailSendinBlue();
            } else {
                //Server
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                $config->nome_empresa . " - Nova solicitação pelo $silicitadoPor",
                $html,
                $email,
                $email
            );
            return $res;
        } catch (Exception $e) {
            return $e;
        }
    }


    public static function enviarEmailSuperAdminNovoUsuario($email, $tipo)
    {
        $config = self::getConfig();
        $aux = $email;

        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h2>Um novo {$tipo} acaba de se cadastrar pelo App.</h2>
                                    <h2 style='text-align: center;'>Acesse o painel administrativo com o e-mail $aux e acompanhe.</h2>
                                </div>
                                <p style='text-align: center;' align='center'>
                                    <h5>Acesse o painel administrativo</h5>
                                    <a style='padding: 8px; border: 1px solid #ededed; font-size: 21px;' href='https://admin2.casadosindico.srv.br/admin'>Acessar o painel</a>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($tipo == "sendingblue") {
                //Sendingblue
                $sender = new EnviarEmailSendinBlue();
            } else {
                //Server
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                $config->nome_empresa . " - Usuário Novo pelo App",
                $html,
                $email,
                $email
            );
            return $res;
        } catch (Exception $e) {
            return false;
        }
    }


    public static function enviarEmailAfiliadosNovaSolicitacao($email, $nome_afiliado, $id, $host = "sendinblue")
    {

        $config = Configuracao::orderBy("id", "DESC")->first();
        $link_android = "";
        $link_ios = "";

        if ($config['link_android']) {
            $link_android = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config['link_android']}'>
                                <img alt=''src='https://casadosindico.srv.br/assets/playstore.png' />
                             </a>";
        }

        if ($config['link_ios']) {
            $link_ios = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config['link_ios']}'>
                            <img alt=''src='https://casadosindico.srv.br/assets/appstore.png' />
                            </a>";
        }

        $aux = $email;
        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h1>A solicitação #$id de um síndico acaba de chegar para você e aguarda o seu parecer.</h1>
                                    <h2 style='text-align: center;'>Acesse o aplicativo Casa do Síndico com o e-mail $aux e confira.</h2>
                                </div>
                                <p style='text-align: center;'>
                                    <h3>Baixe o aplicativo em uma das lojas</h3>
                                    $link_android &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    $link_ios
                                    <p>
                                        Se você não lembrar da sua senha, baixe o aplicativo, clique em <b>Já possuo uma conta</b> e depois clique em <b>Esqueci minha senha</b>. Você receberá uma senha nova neste mesmo e-mail.
                                    </p>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($host == "sendinblue") {
                $sender = new EnviarEmailSendinBlue();
            } else {
                $sender = new EnviarEmail();
            }

            $res = $sender->send(
                "Casa do Síndico - Nova solicitação pelo App",
                $html,
                $email,
                $nome_afiliado,
            );
            return $res;
        } catch (Exception $e) {
            return false;
        }
    }




    public static function enviarEmailAfiliadosNovaSolicitacaoMultiEmail($emails, $tipo = "sendingblue")
    {

        $config = Configuracao::orderBy("id", "DESC")->first();
        $link_android = "";
        $link_ios = "";

        if ($config['link_android']) {
            $link_android = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config['link_android']}'>
                                <img alt=''src='https://casadosindico.srv.br/assets/playstore.png' />
                             </a>";
        }

        if ($config['link_ios']) {
            $link_ios = "<a style='padding: 8px; border: 1px solid #ededed;' href='{$config['link_ios']}'>
                            <img alt=''src='https://casadosindico.srv.br/assets/appstore.png' />
                            </a>";
        }

        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h1>A solicitação #" . $emails[0]["orcamento_id"] . " de um síndico acaba de chegar para você e aguarda o seu parecer.</h1>
                                    <h2 style='text-align: center;'>Acesse o aplicativo Casa do Síndico e confira.</h2>
                                </div>
                                <p style='text-align: center;'>
                                    <h3>Baixe o aplicativo em uma das lojas</h3>
                                    $link_android &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    $link_ios
                                    <p>
                                        Se você não lembrar da sua senha, baixe o aplicativo, clique em <b>Já possuo uma conta</b> e depois clique em <b>Esqueci minha senha</b>. Você receberá uma senha nova neste mesmo e-mail.
                                    </p>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($tipo == "sendinblue") {
                $sender = new EnviarEmailSendinBlue();
            } else {
                $sender = new EnviarEmail();
            }

            foreach ($emails as $e) {
                $sender->addCc($e['email'], $e['razao_social']);
            }
            $res = $sender->sendCc(
                "Casa do Síndico - Nova solicitação pelo App",
                $html
            );

            return $res;
        } catch (Exception $e) {
            return false;
        }
    }



    public static function enviarEmailNovaAssinatura($email, $nome, $razao_social, $tipo = "sendingblue")
    {
        $config = self::getConfig();
        $aux = $email;

        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h2>O afiliado $razao_social solicitou uma nova assinatura na sua região e está aguardando o contrato.</h2>
                                    <h2 style='text-align: center;'>Acesse o painel administrativo com o e-mail $aux e confira.</h2>
                                </div>
                                <p style='text-align: center;' align='center'>
                                    <h5>Acesse o painel administrativo</h5>
                                    <a style='padding: 8px; border: 1px solid #ededed; font-size: 21px;' href='https://admin2.casadosindico.srv.br/admin_franqueado'>Acessar o painel</a>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($tipo == "sendingblue") {
                //Sendingblue
                $sender = new EnviarEmailSendinBlue();
            } else {
                //Server
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                $config->nome_empresa . " - Nova assinatura pelo App",
                $html,
                $email,
                $nome
            );
            return $res;
        } catch (Exception $e) {
            return false;
        }
    }


    public static function enviarEmailNovoInteressado($email, $nome, $razao_social, $id, $tipo = "sendingblue")
    {
        $config = self::getConfig();
        $aux = $email;

        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h2>$razao_social se interessou pela solicitação #$id.</h2>
                                    <h2 style='text-align: center;'>Acesse o painel administrativo com o e-mail $aux e confira.</h2>
                                </div>
                                <p style='text-align: center;' align='center'>
                                    <h5>Acesse o App e confira</h5>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($tipo == "sendingblue") {
                //Sendingblue
                $sender = new EnviarEmailSendinBlue();
            } else {
                //Server
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                $config->nome_empresa . " - Novo interessado",
                $html,
                $email,
                $nome
            );
            return $res;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function enviarEmailAceitarOrcamento($email, $nome, $nome_sindico, $id, $tipo = "sendingblue")
    {
        $config = self::getConfig();
        $aux = $email;

        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h2>O síndico $nome_sindico deseja receber sua visita para um orçamento para a solicitação #$id.</h2>
                                </div>
                                <p style='text-align: center;' align='center'>
                                    <h5>Acesse o App e confira</h5>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($tipo == "sendingblue") {
                //Sendingblue
                $sender = new EnviarEmailSendinBlue();
            } else {
                //Server
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                $config->nome_empresa . " - Síndico aguarda o seu orçamento",
                $html,
                $email,
                $nome
            );
            return $res;
        } catch (Exception $e) {
            return false;
        }
    }


    public static function enviarEmailRecusarOrcamento($email, $nome, $nome_sindico, $id, $host = "sendinblue")
    {
        $config = self::getConfig();
        $aux = $email;

        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h2>O síndico rejeitou seu orçamento para o solicitação #$id.</h2>
                                </div>
                                <p style='text-align: center;' align='center'>
                                    <h5>Acesse o App e confira</h5>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($host == "sendinblue") {
                $sender = new EnviarEmailSendinBlue();
            } else {
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                $config->nome_empresa . " - Síndico aguarda o seu orçamento",
                $html,
                $email,
                $nome
            );
            return $res;
        } catch (Exception $e) {
            return false;
        }
    }



    public static function enviarEmailAlteracaoStatusOrcamentoAfiliado($email, $nome, $orcamento, $tipo_usuario, $valor = null, $tipo = "sendingblue")
    {
        $config = self::getConfig();
        $aux = $email;

        $mensagem = "";
        if ($tipo_usuario == "sindico") {
            if ($orcamento->status_afiliado == 5) {
                $mensagem = "O prestador de serviço da solicitação #" . $orcamento->id . " CONCLUIU o serviço.";
            } elseif ($orcamento->status_afiliado == 9) {
                $mensagem = "O prestador de serviço da solicitação #" . $orcamento->id . " CANCELOU o serviço.";
            }
        } elseif ($tipo_usuario == "afiliado") {
            if ($orcamento->status_sindico == 5) {
                $mensagem = "O síndico do serviço da solicitação #" . $orcamento->id . " CONCLUIU o serviço.";
            } elseif ($orcamento->status_sindico == 9) {
                $mensagem = "O síndico do serviço da solicitação #" . $orcamento->id . " CANCELOU o serviço.";
            } elseif ($orcamento->status_sindico == 2) {
                if ($valor)
                    $mensagem = "O síndico do serviço da solicitação #" . $orcamento->id . " está analisando os orçamentos. Sua proposta foi de R$" . $valor . ".";
                else
                    $mensagem = "O síndico do serviço da solicitação #" . $orcamento->id . " está analisando os orçamentos. Encaminhe sua proposta.";
            }
        }

        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h2>$mensagem.</h2>
                                    <h2 style='text-align: center;'>Acesse o Aplicativo com o e-mail $aux.</h2>
                                </div>
                                <p style='text-align: center;' align='center'>
                                    <h5>Acesse o App e confira</h5>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($tipo == "sendingblue") {
                //Sendingblue
                $sender = new EnviarEmailSendinBlue();
            } else {
                //Server
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                $config->nome_empresa . " - Alteração de status da solicitação",
                $html,
                $email,
                $nome
            );
            return $res;
        } catch (Exception $e) {
            return false;
        }
    }


    public static function enviarEmailAlteracaoStatusOrcamentoFranqueado($email, $nome, $orcamento, $tipo_usuario_alterou, $tipo = "sendingblue")
    {
        $config = self::getConfig();
        $aux = $email;

        $mensagem = "";
        if ($tipo_usuario_alterou == "sindico") {
            if ($orcamento->status_afiliado == 5) {
                $mensagem = "O prestador de serviço da solicitação #" . $orcamento->id . " CONCLUIU o serviço.";
            } elseif ($orcamento->status_afiliado == 9) {
                $mensagem = "O prestador de serviço da solicitação #" . $orcamento->id . " CANCELOU o serviço.";
            }
        } elseif ($tipo_usuario_alterou == "afiliado") {
            if ($orcamento->status_sindico == 5) {
                $mensagem = "O síndico do serviço da solicitação #" . $orcamento->id . " CONCLUIU o serviço.";
            } elseif ($orcamento->status_sindico == 9) {
                $mensagem = "O síndico do serviço da solicitação #" . $orcamento->id . " CANCELOU o serviço.";
            } elseif ($orcamento->status_sindico == 2) {
                $mensagem = "O síndico do serviço da solicitação #" . $orcamento->id . " está analisando os orçamentos.";
            }
        }

        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td style='text-transform: uppercase;'>{$config->nome_empresa}</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 300px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img alt=''style='' src='{$config->logo}'>
                                    <h2>$mensagem.</h2>
                                    <h2 style='text-align: center;'>Acesse o Aplicativo com o e-mail $aux.</h2>
                                </div>
                                <p style='text-align: center;' align='center'>
                                    <h5>Acesse o App e confira</h5>
                                </p>
                                <p>
                                    Se você não reconhece este e-mail, por favor, desconsidere-o.
                                </p>
                                
								<p>
                                    Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>{$config->nome_empresa}.</b><br>
                                {$config->endereco}
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($tipo == "sendingblue") {
                //Sendingblue
                $sender = new EnviarEmailSendinBlue();
            } else {
                //Server
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                $config->nome_empresa . " - Alteração de status da solicitação",
                $html,
                $email,
                $nome
            );
            return $res;
        } catch (Exception $e) {
            return false;
        }
    }






    public static function boasVindasSindico($email, $nome, $senha, $novoUsuario, $usuarioId, $tipo = "sendingblue")
    {
        $config = self::getConfig();
        $aux = $email;

        $link_android = "<a style='padding: 8px; border: 1px solid #ededed;' href='https://play.google.com/store/apps/details?id=br.srv.casadosindico'>
							<img alt=''src='https://casadosindico.srv.br/assets/playstore.png' />
						 </a>";


        $link_ios = "<a style='padding: 8px; border: 1px solid #ededed;' href=''>
						<img alt=''src='https://casadosindico.srv.br/assets/appstore.png' />
						</a>";
        $link_ios = "";


        $html = "<table width='100%'>
                    <tbody>
                        <tr>
                            <td>CASA DO SÍNDICO</td>
                        </tr>
                        <tr>
                            <td>
                                <div style='text-align: center; background-color: #fff; width: 100%; max-width: 420px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <img style='' alt='' src='https://casadosindico.srv.br/assets/images/casa_logo.png'>
                                    <h1>Seja bem-vindo a Casa do Síndico</h1>
                                    <h2 style='text-align: center;'>Você está recebendo este e-mail para verificação da conta do aplicativo Casa do Síndico</h2>
                                </div>
                                <div style='text-align: center; background-color: #fff; width: 100%; max-width: 420px; margin: auto; max-width: 100%; padding: 16px; border: 1px solid #ccc; border-radius: 8px; margin-top: 35px;'>
                                    <p style='text-align: center;' align='center'>
                                        <h5>Por favor, clique no link para verificar sua conta de e-mail</h5>
                                        <a style='padding: 8px; border: 1px solid #ededed; font-size: 21px;' href='https://casadosindico.srv.br/confirmarConta.php?q=" . md5(md5($usuarioId)) . "'>Verificar e-mail</a>
                                    </p>
                                    <p style='text-align: center;'>
                                        <h3>Baixe o aplicativo em uma das lojas</h3>
                                        <h4>Nome de usuário: $email</h4>
                                        " . ($novoUsuario == true ? "<h4>Senha: $senha</h4>" : "") . "
                                        $link_android &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                        $link_ios
                                        <p>
                                            Se você não lembrar da sua senha, baixe o aplicativo, clique em <b>Já possuo uma conta</b> e depois clique em <b>Esqueci minha senha</b>. Você receberá uma senha nova neste mesmo e-mail.
                                        </p>
                                    </p>
                                    <p>
                                        Se você não reconhece este e-mail, por favor, desconsidere-o.
                                    </p>
                                    
                                    <p>
                                        Não quero mais receber e-mails. <a href='https://casadosindico.srv.br/descadastrar'>Descadastrar e-mail</a>
                                    </p>    
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td align='center'>
                                <br><br>
                                Equipe, <b>Casa do Síndico.</b><br>
                                " . $config['endereco'] . "
                                <br>
                                contato@casadosindico.srv.br
                            </td>
                        </tr>
                    </tbody></table>";

        try {
            if ($tipo == "sendingblue") {
                //Sendingblue
                $sender = new EnviarEmailSendinBlue();
            } else {
                //Server
                $sender = new EnviarEmail();
            }
            $res = $sender->send(
                $config->nome_empresa . " - Nova solicitação pelo App",
                $html,
                $email,
                $nome
            );
            return $res;
        } catch (Exception $e) {
            return false;
        }
    }

    public static function sendEmailAdminsAndFranqueados($orcamento, $tipo = "sendingblue")
    {
        $regiao = FranqueadoRegiao::where("regiao_id", $orcamento->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
        if ($regiao) {
            $franqueado = Franqueado::where("id", $regiao->franqueado_id)->first();
            if ($orcamento->id > 0) {
                if ($franqueado) {
                    SenderEmails::enviarEmailFranqueado($franqueado->email, $franqueado->nome, $orcamento->id, $tipo);
                    SenderEmails::enviarEmailSuperAdmin("renato.paranagua@hotmail.com", $franqueado->nome, $orcamento->id, $tipo);
                    if (getenv('APP_DEBUG') == false) {
                        SenderEmails::enviarEmailSuperAdmin("contato@casadosindico.srv.br", $franqueado->nome, $orcamento->id, $tipo);
                        SenderEmails::enviarEmailSuperAdmin("adm@casadosindico.srv.br", $franqueado->nome, $orcamento->id, $tipo);
                    }
                } else {
                    SenderEmails::enviarEmailSuperAdmin("renato.paranagua@hotmail.com", "SEM FRANQUIA", $orcamento->id, $tipo);
                    if (getenv('APP_DEBUG') == false) {
                        SenderEmails::enviarEmailSuperAdmin("contato@casadosindico.srv.br",  "SEM FRANQUIA", $orcamento->id, $tipo);
                        SenderEmails::enviarEmailSuperAdmin("adm@casadosindico.srv.br",  "SEM FRANQUIA", $orcamento->id, $tipo);
                    }
                }
            }
        }
    }


    public static function senderEnviarEmailAfiliados($orcamento, $tipo = "sendingblue")
    {

        $afiliadosEnviar = [];
        $afiliadosCategorias = AfiliadoCategorium::where("categoria_id", $orcamento->categoria_id)->where("status", "aprovado")->get();
        $inadimplenciaFranquia = [];
        foreach ($afiliadosCategorias as $afiliadoCat) {

            $afiliado = Afiliado::where("id", $afiliadoCat->afiliado_id)->first();

            if ($afiliado) {
                $afiliadosRegiaoLista = AfiliadoRegiao::where("regiao_id", $orcamento->regiao_id)->where("afiliado_id", $afiliadoCat->afiliado_id)->get();
                foreach ($afiliadosRegiaoLista as $afiliadosRegiao) {
                    if ($afiliadosRegiao) {
                        $planoRegiao = PlanoAssinaturaAfiliadoRegiao::where("id", $afiliadosRegiao->plano_assinatura_afiliado_regiao_id)->where("statusPlano", 1)->orderBy("id", "desc")->first();
                        if ($planoRegiao) {
                            if ($planoRegiao->gerenciado_plano_assas_franquia === null && $planoRegiao->asaas_assinatura_id == null) {
                                $planoRegiao->gerenciado_plano_assas_franquia = 1;
                                $planoRegiao->save();
                            } else if ($planoRegiao->gerenciado_plano_assas_franquia === null && $planoRegiao->asaas_assinatura_id != null) {
                                $planoRegiao->gerenciado_plano_assas_franquia = 0;
                                $planoRegiao->save();
                            }

                            if ($planoRegiao) {
                                $autorizeAsaas = false;
                                $autorizeAutentique = false;
                                //Plano subscription asaas geerenciado pela franquia
                                if ($planoRegiao->gerenciado_plano_assas_franquia == 1 && $planoRegiao->statusPlano == StatusPlano::$ATIVO) {
                                    $autorizeAsaas = true;
                                } else if ($planoRegiao->gerenciado_plano_assas_franquia == 0 && ($planoRegiao->statusPlano == StatusPlano::$ATIVO || $planoRegiao->statusPlano == StatusPlano::$INADIMPLENTE || $planoRegiao->statusPlano == StatusPlano::$EM_PROCESSO_CANCELAMENTO) && $planoRegiao->asaas_assinatura_id != null && $planoRegiao->data_expiracao != null) {
                                    $diasAtrasado = Formatacao::diasPeriodo(date("Y-m-d"), $planoRegiao->data_expiracao);
                                    if ($diasAtrasado >= -10) {
                                        $autorizeAsaas = true;
                                    }
                                }

                                // if ($planoRegiao->statusPlano != StatusPlano::$CANCELADO) {
                                //     $autorizeAsaas = true;
                                // }

                                if ($planoRegiao->tipo_assinatura == 1 && $planoRegiao->status_afiliado == 1) {
                                    //Altenticado pelo autentique
                                    $autorizeAutentique = true;
                                } else if ($planoRegiao->tipo_assinatura == 2) {
                                    //Autenticado pela franquia
                                    $autorizeAutentique = true;
                                }




                                $isInadimplente = true;
                                $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $orcamento->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
                                if ($franqueadoRegiao) {
                                    $franqueado = Franqueado::where("id", $franqueadoRegiao->franqueado_id)->first();
                                    if ($franqueado) {
                                        $afiliadoFranqueadoAsaas = AfiliadoFranqueadoAsaas::where("afiliado_id", $afiliadosRegiao->afiliado_id)->where("franqueado_id", $franqueado->id)->orderBy("id", "desc")->first();
                                        if ($afiliadoFranqueadoAsaas) {
                                            $vencidas =  $afiliadoFranqueadoAsaas->asaas_cobrancas_vencidas ? json_decode($afiliadoFranqueadoAsaas->asaas_cobrancas_vencidas) : [];
                                            $isInadimplente = Asaas::isPossuiCobrancaVencida($vencidas);
                                        } elseif ($planoRegiao->gerenciado_plano_assas_franquia == 1 && $planoRegiao->statusPlano == StatusPlano::$ATIVO) {
                                            $isInadimplente = false;
                                        }
                                    }
                                }

                                if ($afiliadosRegiao && $afiliadosRegiao->afiliado_id) {
                                    $afiliado = Afiliado::where("id", $afiliadosRegiao->afiliado_id)->first();
                                    if ($afiliado && $afiliado->usuario_app_id) {
                                        $usuarioApp = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
                                        //Verifica se está autorizado a ver este orçamento
                                        if ($autorizeAsaas && $autorizeAutentique && $usuarioApp && $usuarioApp->data_confirmacao && $isInadimplente == false) {
                                            $afiliadosEnviar[$afiliado->id] = $afiliado;
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $listaEmailsEnviar = [];
        $listaNotifications = [];
        foreach ($afiliadosEnviar as $afil) {
            Notificacao::painelNotificarAfiliadoNovaSolicitacao($orcamento, $afil);

            $usuarioApp = UsuarioApp::where("id", $afil->usuario_app_id)->first();
            if ($usuarioApp && $usuarioApp->token_notification) {
                $condominio = Condominio::withTrashed()->where("id", $orcamento->condominio_id)->first();
                if ($condominio) {
                    $listaNotifications[] = [
                        "orcamento_id" => $orcamento->id,
                        "token_notification" => $usuarioApp->token_notification,
                        "nome_condominio" => $condominio->nome
                    ];
                }
            }

            if ($usuarioApp && $usuarioApp->email) {
                $listaEmailsEnviar[] = [
                    "email" => $usuarioApp->email,
                    "razao_social" => $afil->razao_social,
                    "orcamento_id" => $orcamento->id,
                    "tipo" => $tipo
                ];
            }
        }

        $log = new LogSystem([
            "data_cadastro" => Carbon::now(),
            "time_inicio" => time(),
            "descricao" => "Notificando " . count($listaEmailsEnviar) . " afiliados",
            "endpoint" => "--",
            "metodo" => "E-MAIL",
            "usuario_app_id" => $afil->usuario_app_id,
            "body" => "",
            "response" => json_encode($listaEmailsEnviar)
        ]);
        $log->save();

        SenderEmails::enviarEmailAfiliadosNovaSolicitacaoMultiEmail($listaEmailsEnviar);
        foreach ($listaNotifications as $notifs) {
            SenderNotificacao::enviarNotificacaoNovaSolicitacao($notifs['orcamento_id'], $notifs['token_notification'], $notifs['nome_condominio']);
        }
    }


    public static function senderEnviarEmailOrcamentoUpdate($orcamento, $tipo_usuario, $tipo = "sendingblue")
    {
        if ($tipo_usuario == "sindico") {
            $afiliadosInteressados = AfiliadoOrcamentoInteresse::where("orcamento_id", $orcamento->id)->where("descartado_afiliado", 0)->whereIn("descartado_sindico", [-1, 0])->get();

            if ($afiliadosInteressados && $orcamento->afiliado_id == null) {
                foreach ($afiliadosInteressados as $afiliadoInteresse) {
                    $afiliado = Afiliado::where("id", $afiliadoInteresse->afiliado_id)->first();
                    if ($afiliado) {
                        $usuarioApp = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
                        $mensagem = "Orçamento atualizado. Confira.";
                        if ($usuarioApp && $usuarioApp->token_notification) {
                            $mensagem = SenderNotificacao::enviarNotificacaoAlteracaoStatusOrcamento($usuarioApp->token_notification, $orcamento, "sindico", $afiliadoInteresse->valor_orcamento);
                        }
                        if ($usuarioApp && $usuarioApp->email) {
                            SenderEmails::enviarEmailAlteracaoStatusOrcamentoAfiliado($usuarioApp->email, $afiliado->razao_social, $orcamento, "sindico", $afiliadoInteresse->valor_orcamento, $tipo);
                        }
                        Notificacao::painelNotificarAfiliadoALteracaoStatus($orcamento, $afiliado, $mensagem);
                    }
                }
            } else {
                $afiliado = Afiliado::where("id", $orcamento->afiliado_id)->first();
                if ($afiliado) {
                    $usuarioApp = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
                    $mensagem = "Orçamento atualizado. Confira.";
                    if ($usuarioApp && $usuarioApp->token_notification) {
                        $mensagem = SenderNotificacao::enviarNotificacaoAlteracaoStatusOrcamento($usuarioApp->token_notification, $orcamento, "afiliado", null);
                    }
                    if ($usuarioApp && $usuarioApp->email) {
                        SenderEmails::enviarEmailAlteracaoStatusOrcamentoAfiliado($usuarioApp->email, $afiliado->razao_social, $orcamento, "afiliado", null, $tipo);
                    }
                    Notificacao::painelNotificarAfiliadoALteracaoStatus($orcamento, $afiliado, $mensagem);
                }
            }
        } elseif ($tipo_usuario == "afiliado") {
            $condominio = Condominio::withTrashed()->where("id", $orcamento->condominio_id)->first();
            $sindico = $condominio->sindico()->first();
            if ($sindico) {
                $usuarioApp = UsuarioApp::withTrashed()->where("id", $sindico->usuario_app_id)->first();
                $mensagem = "Orçamento atualizado. Confira.";
                if ($usuarioApp && $usuarioApp->token_notification) {
                    $mensagem = SenderNotificacao::enviarNotificacaoAlteracaoStatusOrcamento($usuarioApp->token_notification, $orcamento, "sindico");
                }

                if ($usuarioApp && $usuarioApp->email) {
                    SenderEmails::enviarEmailAlteracaoStatusOrcamentoAfiliado($usuarioApp->email, $condominio->sindico->nome, $orcamento, "sindico", $tipo);
                }
                Notificacao::painelNotificarSindicoAlteracaoStatus($orcamento, $sindico, $mensagem);
            }
        }

        if ($orcamento->regiao_id > 0) {
            $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $orcamento->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
            if ($franqueadoRegiao) {
                if ($franqueadoRegiao->franqueado) {
                    $franqueado = $franqueadoRegiao->franqueado;
                    SenderEmails::enviarEmailAlteracaoStatusOrcamentoFranqueado($franqueado->email, $franqueado->nome, $orcamento, $tipo_usuario, $tipo);
                }
            }
        }
    }
}
