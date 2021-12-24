<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\ImagemOrcamento;
use App\Http\Resources\UsuarioApp as ResourcesUsuarioApp;
use App\Models\AceiteTermos;
use App\Models\Afiliado;
use App\Models\BO\UsuarioAppBO;
use App\Models\CartaoCnpj;
use App\Models\Configuracao;
use App\Models\ContratoSocial;
use App\Models\ImagemOrcamento as ModelsImagemOrcamento;
use App\Models\Sindico;
use App\Models\Usuario;
use App\Models\UsuarioApp;
use App\Models\Vistoriador;
use App\Models\VistoriaImagem;
use App\Util\Validacao;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\File\File;



class UsuarioAppController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request, null);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->newLog("Novo usuário");
        DB::beginTransaction();
        try {
            $usuario_app = new UsuarioApp();
            $usuario_app->email = isset($request['email']) ? (string) strtolower(trim($request['email'])) : "";
            $usuario_app->senha = isset($request['senha']) ? (string) $request['senha'] : "";
            $usuario_app->tipo = $request['tipo'];
            $usuario_app->imagem = $request['imagem'] ? $request['imagem'] : "no-image-perfil.png";
            $usuario_app->isFacebook = isset($request['isFacebook']) ? $request['isFacebook'] : 0;
            $usuario_app->isEmail = isset($request['isEmail']) ? $request['isEmail'] : 0;

            $validacao = UsuarioAppBO::validarUsuarioAppSoft($usuario_app);
            $usuario_exixst_app = UsuarioApp::where("email", $usuario_app->email)->where("tipo", $usuario_app->tipo)->get()->first();

            if (!$usuario_exixst_app == null) {
                $validacao->mensagem[] = array("error_code" => "exists-email", "error_message" => "E-mail já cadastrado. Altere o e-mail ou faça o login.");
            }

            if ($validacao->verifica()) {
                $usuario_app->senha = Hash::make($request['senha']);
                if ($usuario_app->isFacebook == 1) {
                    $usuario_app->data_confirmacao = date("Y-m-d H:i:s");
                }
                $usuario_app->save();
                $usuario_app->remember_token = $usuario_app->createToken(md5($request['email']))->accessToken;


                AceiteTermos::novoAceite($usuario_app->id, $request['termos_politica_id'], $request->ip());

                DB::commit();
                try {
                    SenderEmails::enviarEmailSuperAdminNovoUsuario("renato.paranagua@hotmail.com", $usuario_app->tipo);
                    if (getenv("APP_DEBUG") == false) {
                        SenderEmails::enviarEmailSuperAdminNovoUsuario("adm@casadosindico.srv.br", $usuario_app->tipo);
                        SenderEmails::enviarEmailSuperAdminNovoUsuario("contato@casadosindico.srv.br", $usuario_app->tipo);
                    }
                } catch (Exception $e) {
                }
                return $this->successResponse('Usuário criado com sucesso.', $usuario_app);
            } else {
                DB::rollback();
                return $this->errorResponse($validacao->getErros(), 403);
            }
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse($e);
        }
    }


    public function aceite_termos(Request $request)
    {
        $this->newLog("Aceite termos");
        try {
            $res = AceiteTermos::novoAceite($this->usuario_logado->id, $request['termos_politica_id'], $request->ip());
            return $this->successResponse('Termo aceito com sucesso.', $res);
        } catch (Exception $e) {
            DB::rollback();
            return $this->errorResponse('Error processing your request');
        }
    }

    public function verificar_assinatura_termos()
    {
        $this->newLog("Verifica assiantura termo");
        try {
            $lastTermosUsuario = AceiteTermos::termoAssinadoUsuario($this->usuario_logado->id);
            $lastTermosSistema = AceiteTermos::lastTermo();
            if ($lastTermosUsuario == null || ($lastTermosSistema && $lastTermosUsuario && $lastTermosSistema->id > $lastTermosUsuario->termos_politica_id)) {
                $res = false;
            } else {
                $res = true;
            }
            return $this->successResponse('Termo verificado com sucesso.', $res);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\UsuarioApp  $usuarioApp
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        $this->newLog("Buscando dados de usuário");
        $usuario = UsuarioApp::where("id", $this->usuario_logado->id)->first();
        return $this->successResponse('Success', $usuario);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\UsuarioApp  $usuarioApp
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->newLog("Atualiza usuário");
        try {
            $usuario_app = UsuarioApp::findOrFail($id);
            $usuario_app->nome = $this->getValueRequest($request, $usuario_app, 'nome', true);
            $usuario_app->senha = $this->getValueRequest($request, $usuario_app, 'senha', true);
            $validacao = UsuarioAppBO::validarUsuarioApp($usuario_app);

            if (isset($request['senha'])) {
                $usuario_app->senha = Hash::make($request['senha']);
            }
            $usuario_app->update();
            return $this->successResponse('UsuarioApp atualizado!', $usuario_app);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse('Error processing your request');
        }
    }

    public function partial_update(Request $request, $id)
    {
        $this->newLog("Atualiza usuário");
        try {
            $usuario_app = UsuarioApp::findOrFail($id);
            $usuario_app->nome = $this->getValueRequest($request, $usuario_app, 'nome', true);
            $usuario_app->senha = $this->getValueRequest($request, $usuario_app, 'senha', true);
            $validacao = UsuarioAppBO::validarUsuarioApp($usuario_app);

            if (isset($request['senha'])) {
                $usuario_app->senha = Hash::make($request['senha']);
            }

            $usuario_app->update();
            return $this->successResponse('UsuarioApp atualizado!', $usuario_app);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse('Error processing your request');
        }
    }

    public function usuario_facebook(Request $request)
    {
        $this->newLog("Usuário facebook");
        try {
            if ($request['usuario']['tipo']) {
                $usuarioApp = UsuarioApp::where("email", trim($request['usuario']['email']))->where("tipo", $request['usuario']['tipo'])->first();
            } else {
                $usuariosApp = UsuarioApp::where("email", trim($request['usuario']['email']))->get();
            }

            if (isset($usuariosApp) && $usuariosApp && count($usuariosApp) > 1) {
                foreach ($usuariosApp as $user) {
                    $user->isFacebook = 1;
                    if ($user->data_confirmacao == null) {
                        $user->data_confirmacao = date("y-m-d H:i:s");
                    }
                    if ($user->imagem == "")
                        $user->imagem = $request['usuario']['imagem'];

                    $user->update();
                    $user->remember_token = $user->createToken(md5($request['usuario']['email'] . $request['usuario']['tipo']))->accessToken;
                }
            }

            if (isset($usuariosApp) && $usuariosApp && count($usuariosApp) == 1) {
                $usuarioApp = $usuariosApp[0];
            }

            if (isset($usuarioApp) && $usuarioApp) {
                $usuarioApp->isFacebook = 1;
                if ($usuarioApp->data_confirmacao == null) {
                    $usuarioApp->data_confirmacao = date("y-m-d H:i:s");
                }

                if ($usuarioApp->imagem == "")
                    $usuarioApp->imagem = $request['usuario']['imagem'];

                $usuarioApp->update();
                $usuarioApp->remember_token = $usuarioApp->createToken(md5($request['usuario']['email']))->accessToken;
            } else {
                $usuarioApp = new Usuario();
                $usuarioApp->id = 0;
            }

            if (isset($usuariosApp) && count($usuariosApp) > 1) {
                return $this->successResponse('Success', $usuariosApp);
            } elseif (isset($usuarioApp)) {
                return $this->successResponse('Success', $usuarioApp);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function addTokenFCM(Request $request)
    {
        $this->newLog("Token FCM adicionado");
        try {
            if (isset($request['token']) && strlen($request['token']) > 15) {
                $this->user->token_notification = $request['token'];
                $this->user->update();
                return $this->successResponse('Success', $this->user);
            }
            return $this->errorResponse('Error processing your request');
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /* ENVIO DE E-MAILS RELACIONADO AOS USUÁRIOS */
    public function sendEmailConfirmacao(Request $request)
    {
        $this->newLog("Envio de e-mail de confirmação");
        $usuario = UsuarioApp::where("id", $this->usuario_logado->id)->first();
        if ($usuario) {
            $usuario->utimo_envio_email = Carbon::now();
            $usuario->save();
        }
        return SenderEmails::emailConfirmacao($this, $request);
    }

    public function novasenha(Request $request)
    {
        $this->newLog("Nova senha");
        return SenderEmails::novasenha($this, $request);
    }

    public function novasenhaPerfil(Request $request)
    {
        $this->newLog("Nova senha perfil");
        return SenderEmails::novasenhaPerfil($this, $request);
    }

    public function upload(Request $request)
    {
        $this->newLog("Upload de arquivos");
        try {
            $tipo_arquivo = $request['tipo'];
            $tipo_usuario = $this->user->tipo;
            $usuarioApp = UsuarioApp::where("id", $this->user->id)->first();

            if ($tipo_usuario == "afiliado") {
                $afiliado = Afiliado::where("usuario_app_id", $this->user->id)->first();
            } else if ($tipo_usuario == "sindico") {
                $sindico = Sindico::where("usuario_app_id", $this->user->id)->first();
            } else if ($tipo_usuario == "vistoriador") {
                $vistoriador = Vistoriador::where("usuario_app_id", $this->user->id)->first();
            }


            if ($tipo_arquivo == "contrato_social") {
                $pasta = "contratosocial";
                $extensoesAceitaveis = ["pdf", "png", "jpg", "png", "jpeg"];
            } elseif ($tipo_arquivo == "cartao_cnpj") {
                $pasta = "cartaocnpj";
                $extensoesAceitaveis = ["pdf", "png", "jpg", "png", "jpeg"];
            } elseif ($tipo_arquivo == "logo") {
                $pasta = "logo";
                $extensoesAceitaveis = ["png", "jpg", "png", "jpeg"];
            } elseif ($tipo_arquivo == "logo_afiliado") {
                $pasta = "logo";
                $extensoesAceitaveis = ["png", "jpg", "png", "jpeg"];
            } elseif ($tipo_arquivo == "foto_perfil") {
                $pasta = "logo";
                $extensoesAceitaveis = ["png", "jpg", "png", "jpeg"];
            }
            //return $request['file'];
            $arquivo = $request['file'];

            $ext = $arquivo->getClientOriginalExtension();

            if ($arquivo->getSize() > $arquivo->getMaxFilesize()) {
                return [
                    "error" => "O arquivo não exceder " . ($arquivo->getMaxFilesize() / 1024 / 1024) . "Mb"
                ];
            }
            if (!in_array($ext, $extensoesAceitaveis)) {
                return [
                    "error" => "O formato do arquivo deve ser PDF ou imagem"
                ];
            }

            $caminho = "$tipo_usuario/$pasta/" . md5($this->user->id . rand(1, 9999) . $arquivo->getClientOriginalName()) . "." . $ext;
            $nomeArquivo = "../../admincasadosindico/storage/app/public/" . $caminho;
            //$arquivoFisico = $_FILES['file']['tmp_name'];

            //Upload de arquivo
            //$resUpload = move_uploaded_file($arquivoFisico, $nomeArquivo);

            $caminhoRelativo = "$tipo_usuario/$pasta";
            $caminhoAbsoluto = "../../admincasadosindico/storage/app/public/" . $caminhoRelativo;
            $nomeArquivo = md5($this->user->id . rand(1, 9999) . $arquivo->getClientOriginalName()) . "." . $ext;
            $resUpload = $arquivo->move($caminhoAbsoluto, $nomeArquivo);

            $caminhoSaveBD = $caminhoRelativo . "/" . $nomeArquivo;

            $dados = [];

            if ($resUpload) {

                if ($tipo_arquivo == "contrato_social") {
                    $dados = [
                        "status" => "pendente",
                        "arquivo" => $caminhoSaveBD,
                        "afiliado_id" => $afiliado->id
                    ];
                    ContratoSocial::create($dados);
                    return ContratoSocial::orderBy("id", "desc")->where("afiliado_id", $afiliado->id)->first();
                } elseif ($tipo_arquivo == "cartao_cnpj") {
                    $dados = [
                        "status" => "pendente",
                        "arquivo" => $caminhoSaveBD,
                        "afiliado_id" => $afiliado->id
                    ];

                    return CartaoCnpj::orderBy("id", "desc")->where("afiliado_id", $afiliado->id)->first();
                } elseif ($tipo_arquivo == "logo_afiliado") {
                    $afiliado->logo = $caminhoSaveBD;
                    $afiliado->update();
                    return $afiliado;
                } elseif ($tipo_arquivo == "foto_perfil") {
                    $usuarioApp->imagem = $caminhoSaveBD;
                    $usuarioApp->update();
                    return $usuarioApp;
                } elseif ($tipo_arquivo == "foto_vistoria") {
                    $usuarioApp->imagem = $caminhoSaveBD;
                    $usuarioApp->update();
                    return $usuarioApp;
                }
            } else {
                return [
                    "error" => "Falha ao realizar upload"
                ];
            }
        } catch (Exception $e) {

            return [
                "error" => $e->getMessage()
            ];
        }
    }


    public function uploadBase64(Request $request)
    {
        $this->newLog("Upload de arquivos");
        try {
            $tipo_arquivo = $request['tipo'];

            $tipo_usuario = $this->user->tipo;
            $usuarioApp = UsuarioApp::where("id", $this->user->id)->first();

            if ($tipo_usuario == "afiliado") {
                $afiliado = Afiliado::where("usuario_app_id", $this->user->id)->first();
            } else if ($tipo_usuario == "sindico") {
                $sindico = Sindico::where("usuario_app_id", $this->user->id)->first();
            } else if ($tipo_usuario == "vistoriador") {
                $vistoriador = Vistoriador::where("usuario_app_id", $this->user->id)->first();
            }


            if ($tipo_arquivo == "contrato_social") {
                $pasta = "contratosocial";
                $extensoesAceitaveis = ["pdf"];
            } elseif ($tipo_arquivo == "cartao_cnpj") {
                $pasta = "cartaocnpj";
                $extensoesAceitaveis = ["pdf"];
            } elseif ($tipo_arquivo == "logo") {
                $pasta = "logo";
                $extensoesAceitaveis = ["png", "jpg", "png", "jpeg"];
            } elseif ($tipo_arquivo == "logo_afiliado") {
                $pasta = "logo";
                $extensoesAceitaveis = ["png", "jpg", "png", "jpeg"];
            } elseif ($tipo_arquivo == "foto_perfil") {
                $pasta = "logo";
                $extensoesAceitaveis = ["png", "jpg", "png", "jpeg"];
            } elseif ($tipo_arquivo == "foto_orcamento") {
                $pasta = "foto_orcamento";
                $extensoesAceitaveis = ["png", "jpg", "png", "jpeg"];
            } elseif ($tipo_arquivo == "foto_vistoria") {
                $pasta = "foto_vistoria";
                $extensoesAceitaveis = ["png", "jpg", "png", "jpeg"];
            }

            if ($request['file'] == "") {
                return [
                    "error" => "Arquivo vazio."
                ];
            }

            if (isset($request['file']["imagem"]) && isset($request['file']["orcamento_id"])) {
                $orcamento_id = $request['file']["orcamento_id"];
                $descricao = $request['file']["descricao"];
                $request['file'] = $request['file']["imagem"];
            }

            if (isset($request['file']["imagem"]) && isset($request['file']["vistoria_id"])) {
                $vistoria_id = $request['file']["vistoria_id"];
                $descricao = $request['file']["descricao"];
                $request['file'] = $request['file']["imagem"];
            }

            list($header, $arquivoBase64) = explode(",", $request['file']);

            $bin = base64_decode($arquivoBase64);

            $caminhoRelativo = "user-" . $this->user->id . "/$tipo_usuario/$pasta";
            $caminhoAbsoluto = "../../admincasadosindico/storage/app/public/" . $caminhoRelativo;
            if (!file_exists($caminhoAbsoluto)) {
                mkdir($caminhoAbsoluto, 0755, true);
            }

            if ($tipo_arquivo == "contrato_social" || $tipo_arquivo == "cartao_cnpj") {

                $nomeArquivo = md5($this->user->id . rand(1, 9999)) . ".pdf";
                $ext = "pdf";

                $pdf = fopen($caminhoAbsoluto . "/" . $nomeArquivo, 'w');
                fwrite($pdf, $bin);
                //close output file
                fclose($pdf);
                $ext = "pdf";
                $res = true;
            } elseif ($tipo_arquivo == "logo_afiliado" || $tipo_arquivo == "foto_perfil" || $tipo_arquivo == "foto_orcamento" || $tipo_arquivo == "foto_vistoria") {
                $im = imageCreateFromString($bin);
                $ext = "jpg";
                $nomeArquivo = md5($this->user->id . rand(1, 9999)) . "." . $ext;
                $res = imagejpeg($im, $caminhoAbsoluto . "/" . $nomeArquivo, 100);
            }
            $caminhoSaveBD = $caminhoRelativo . "/" . $nomeArquivo;

            if (!in_array($ext, $extensoesAceitaveis)) {
                return [
                    "error" => "O formato do arquivo deve ser PDF ou imagem"
                ];
            }


            $dados = [];

            if ($res) {

                if ($tipo_arquivo == "contrato_social") {
                    $cs = ContratoSocial::where("afiliado_id", $afiliado->id)->get();
                    foreach ($cs as $c) {
                        $c->delete();
                    }
                    $dados = [
                        "status" => "pendente",
                        "arquivo" => $caminhoSaveBD,
                        "afiliado_id" => $afiliado->id
                    ];
                    ContratoSocial::create($dados);
                    $res  = ContratoSocial::orderBy("id", "desc")->where("afiliado_id", $afiliado->id)->first();
                    if ($this->log) {
                        $this->log->updateResponse($res, "OK");
                        $this->log = null;
                    }

                    return $res;
                } elseif ($tipo_arquivo == "cartao_cnpj") {
                    $cc = CartaoCnpj::where("afiliado_id", $afiliado->id)->get();
                    foreach ($cc as $c) {
                        $c->delete();
                    }
                    $dados = [
                        "status" => "pendente",
                        "arquivo" => $caminhoSaveBD,
                        "afiliado_id" => $afiliado->id
                    ];
                    CartaoCnpj::create($dados);

                    $res = CartaoCnpj::orderBy("id", "desc")->where("afiliado_id", $afiliado->id)->first();
                    if ($this->log) {
                        $this->log->updateResponse($res, "OK");
                        $this->log = null;
                    }

                    return $res;
                } elseif ($tipo_arquivo == "logo_afiliado") {
                    $afiliado->logo = $caminhoSaveBD;
                    $afiliado->update();

                    if ($this->log) {
                        $this->log->updateResponse($afiliado, "OK");
                        $this->log = null;
                    }

                    return $afiliado;
                } elseif ($tipo_arquivo == "foto_perfil") {
                    $usuarioApp->imagem = $caminhoSaveBD;
                    $usuarioApp->update();

                    if ($this->log) {
                        $this->log->updateResponse($usuarioApp, "OK");
                        $this->log = null;
                    }

                    return $usuarioApp;
                } elseif ($tipo_arquivo == "foto_vistoria") {
                    $dados = [
                        "vistoria_id" => $vistoria_id,
                        "caminho_imagem" => $caminhoSaveBD,
                        "descricao" => $descricao
                    ];
                    VistoriaImagem::create($dados);

                    if ($this->log) {
                        $this->log->updateResponse($dados, "OK");
                        $this->log = null;
                    }

                    return $dados;
                } elseif ($tipo_arquivo == "foto_orcamento") {
                    $dados = [
                        "orcamento_id" => $orcamento_id,
                        "caminho_imagem" => $caminhoSaveBD,
                        "descricao" => $descricao
                    ];
                    ModelsImagemOrcamento::create($dados);

                    if ($this->log) {
                        $this->log->updateResponse($dados, "OK");
                        $this->log = null;
                    }

                    return $dados;
                }
            } else {
                if ($this->log) {
                    $this->log->updateResponse([
                        "error" => "Falha ao realizar upload"
                    ], "Falha ao realizar upload");
                    $this->log = null;
                }
                return [
                    "error" => "Falha ao realizar upload"
                ];
            }
        } catch (Exception $e) {
            if ($this->log) {
                $this->log->updateResponse([
                    "error" => $e->getMessage()
                ], "Falha ao realizar upload");
                $this->log = null;
            }
            return [
                "error" => $e->getMessage()
            ];
        }
    }




    public function alterar_email(Request $request)
    {
        $this->newLog("Alteração de e-mail");
        try {
            $novo_email = trim($request['novo_email']);

            $validacao = new Validacao();
            $validacao->obrigatorio("email", $novo_email, "E-mail");
            $validacao->email("email", $novo_email, "E-mail");

            if (!$validacao->verifica()) {
                return $this->errorResponse($validacao->getErros(), 403);
            } else {

                $outroUsuario = UsuarioApp::where("email", $novo_email)->where("tipo", $this->user->tipo)->where("id", "<>", $this->user->id)->first();

                if ($outroUsuario) {
                    $validacao->mensagem[] = array("error_code" => "exists-email", "error_message" => "E-mail já está em uso. Altere o e-mail ou faça o login.");
                    return $this->errorResponse($validacao->getErros(), 403);
                } else {
                    if ($this->user->data_confirmacao) {
                        $validacao->mensagem[] = array("error_code" => "exists-email", "error_message" => "Você não pode alterar mais o e-mail, entre em contato com os administradores para isso.");
                        return $this->errorResponse($validacao->getErros(), 403);
                    }
                    $this->user->email = $novo_email;
                    $this->user->update();
                    return $this->successResponse('Success', $this->user);
                }
            }
            return $this->errorResponse('Error processing your request');
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function validarEmail(Request $request)
    {
        $this->newLog("Validação de e-mail");
        $validator1 = $this->getValidatorEmail($request);

        if ($validator1->fails()) {
            return $this->errorResponse([["error_code" => "invalid-email", "error_message" => "E-mail inválido"]], 403);
        }

        $validator2 = $this->getValidatorEmailUnique($request);

        if ($validator2->fails()) {
            return $this->errorResponse([["error_code" => "exists-email", "error_message" => "O e-mail já está em uso"]], 403);
        }
    }

    protected function getValidatorEmail(Request $request)
    {
        $rules = [
            'email' => 'required|email'
        ];
        return Validator::make($request->all(), $rules);
    }

    protected function getValidatorEmailUnique(Request $request)
    {
        $tipo = $request['tipo_usuario'];
        $rules = [
            'email' => 'required|email|unique:usuario_app,email,null,id,deleted_at,NULL,tipo,"' . $tipo . '"'
        ];
        return Validator::make($request->all(), $rules);
    }
}
