<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Models\Afiliado;
use App\Models\AfiliadoCategorium;
use App\Models\AfiliadoRegiao;
use App\Models\Bairro;
use App\Models\BO\OrcamentoBO;
use App\Models\Categoria;
use App\Models\Cidade;
use App\Models\Condominio;
use App\Models\Estado;
use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\LogSendinBlue;
use App\Models\Orcamento;
use App\Models\Parceiro;
use App\Models\PlanoAssinaturaAfiliadoRegiao;
use App\Models\RegiaoFaixaCep;
use App\Models\Sindico as ModelsSindico;
use App\Models\Usuario;
use App\Models\UsuarioApp;
use App\Models\Vistoria;
use App\Util\Formatacao;
use App\Util\ModusOperandiStatus;
use App\Util\StatusOrcamento;
use App\Util\StatusPlano;
use App\Util\Util;
use App\Util\Validacao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class MigradorSiteAppController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request, null);
    }

    public function verifica_cnpj(Request $request)
    {
        $cnpj = $request['cnpj'];
        return $this->successResponse('Sucesso!', Afiliado::where("cnpj", $cnpj)->count());
    }

    public function getCidades($estado_id = null)
    {
        if ($estado_id) {
            $cidades = Cidade::orderBy("nome", "ASC")->where("uf", "=", $estado_id, "or")->where("estado_id", "=", $estado_id, "or")->get();
        } else {
            $cidades = Cidade::orderBy("nome", "ASC")->get();
        }

        return $this->successResponse('Sucesso!', $cidades);
    }

    public function getCidadesIdByCidade($cidade, $estado)
    {
        $cidadeA = Cidade::where("nome", "LIKE", $cidade)->where("uf", $estado)->first();

        if ($cidadeA)
            return $this->successResponse('Sucesso!', $cidadeA->id);
        else
            return $this->successResponse('Sucesso!', false);
    }




    public function getEstados($estado_id = null)
    {
        if ($estado_id > 0) {
            $estados = Estado::orderBy("uf", "ASC")->where("uf", "<>", "")->where("uf", "<>", null)->where("id", $estado_id)->get();
        } else {
            $estados = Estado::orderBy("uf", "ASC")->where("uf", "<>", "")->where("uf", "<>", null)->get();
        }

        return $this->successResponse('Sucesso!', $estados);
    }

    public function getEstadoByUf($uf)
    {
        $estado = Estado::where("uf", $uf)->first();
        if ($estado)
            return $this->successResponse('Sucesso!', $estado->id);
        else
            return $this->successResponse('Sucesso!', 0);
    }

    public function getParceiros()
    {
        $parceiros = Parceiro::where("status", "ativo")->where("logo", "<>", null)->get();
        if ($parceiros)
            return $this->successResponse('Sucesso!', $parceiros);
        else
            return $this->successResponse('Sucesso!', null);
    }

    public function getAfiliados()
    {
        $afiliadosAux = Afiliado::where("logo", "<>", null)->where("logo", "<>", "no-image-perfil.png")->where("logo", "like", "%.jpg")->inRandomOrder()->get();

        $afiliados = [];
        foreach ($afiliadosAux as $afiliado) {
            $regioesAfiliado = AfiliadoRegiao::where("modo", Util::getModusOperandi())->where("afiliado_id", $afiliado->id)->get();
            foreach ($regioesAfiliado as $regAfil) {
                if (isset($regAfil->plano_assinatura_afiliado_regiao_id)) {
                    $planoRegiao = PlanoAssinaturaAfiliadoRegiao::where("id", $regAfil->plano_assinatura_afiliado_regiao_id)->where("statusPlano", 1)->orderBy("id", "desc")->first();
                    $diasAtrasado = 0;
                    if ($regAfil->regiao_id && $planoRegiao && $planoRegiao->statusPlano == StatusPlano::$ATIVO && $planoRegiao->status_afiliado == 1 && $diasAtrasado <= 10) {
                        $afiliados[$afiliado->id] = $afiliado;
                    } else if ($regAfil->regiao_id && $planoRegiao && $planoRegiao->statusPlano == StatusPlano::$INADIMPLENTE && $planoRegiao->status_afiliado == 1 && $diasAtrasado <= 10) {
                        $afiliados[$afiliado->id] = $afiliado;
                    } else if ($regAfil->regiao_id && $planoRegiao && $planoRegiao->asaas_assinatura_id == null && $planoRegiao->statusPlano == 1 && $planoRegiao->status_afiliado == 1) {
                        $afiliados[$afiliado->id] = $afiliado;
                    }
                }
            }
        }
        if ($afiliados)
            return $this->successResponse('Sucesso!', $afiliados);
        else
            return $this->successResponse('Sucesso!', null);
    }

    public function getCategorias()
    {
        $categorias = Categoria::orderBy("nome", "ASC")->where("status", 1)->where("categoria_pai_id", null)->get();
        return $this->successResponse('Sucesso!', $categorias);
    }

    public function getSubcategorias()
    {
        $subcategorias = Categoria::where("categoria_pai_id", "<>", null)->where("status", 1)->orderBy("nome", "ASC")->get();
        return $this->successResponse('Sucesso!', $subcategorias);
    }


    public function getSubcategoriasByCategoria($categoria_id = null)
    {
        if ($categoria_id == null) {
            $subcategorias = Categoria::where("categoria_pai_id", "<>", null)->where("status", 1)->orderBy("nome", "ASC")->get();
            foreach ($subcategorias as $key => $subcategoria) {
                $subcategoria[$key]["categoria"] = Categoria::where("id", $subcategoria->categoria_pai_id)->first();
            }
            return $this->successResponse('Sucesso!', $subcategorias);
        } else {
            $categoria = Categoria::where("id", $categoria_id)->where("status", 1)->first();
            if ($categoria) {
                return $this->successResponse('Sucesso!', $categoria);
            } else {
                return null;
            }
        }
    }

    public function getCategoriasByChaveUrl($chave_url)
    {
        $categoria = Categoria::orderBy("nome", "ASC")->where("status", 1)->where("chave_url", $chave_url)->first();
        return $this->successResponse('Sucesso!', $categoria);
    }


    public function verificarExisteSindico(Request $request)
    {
        $valida = new Validacao();
        $valida->email("email", $request['email'], "E-mail");
        if ($valida->verifica() == false) {
            return $this->successResponse('Sucesso!', "invalid-email");
        }
        $usuario = UsuarioApp::where("email", $request['email'])->where("tipo", "sindico")->first();
        if ($usuario) {
            return $this->successResponse('Sucesso!', "true");
        }
        return $this->successResponse('Sucesso!', "false");
    }

    /*


    $dados['idCategoria'],
            ,
            ,
            ,
            ,
           
            
            $dados['enderecoIP'],


    */

    public function carregarCondominios(Request $dados)
    {
        $usuario_exixst_app = UsuarioApp::where("email", $dados['email'])->where("tipo", "sindico")->first();

        if ($usuario_exixst_app && !(Hash::check($dados['senha'], $usuario_exixst_app->senha) || $usuario_exixst_app->senha == md5($dados['senha']) || $usuario_exixst_app->senha == $dados['senha'])) {
            return $this->successResponse('Sucesso!', false);
        }

        if ($usuario_exixst_app == null) {
            $condominios = [];
        } else {
            $sindico = ModelsSindico::where("usuario_app_id", $usuario_exixst_app->id)->first();
            $condominios = Condominio::where("sindico_id", $sindico->id)->get();
        }

        return $this->successResponse('Sucesso!', $condominios);
    }

    public function cadastrarSolicitacaoSite(Request $dados)
    {
        $this->newLog("Iniciando solicitação de orçamento VIA SITE");
        DB::beginTransaction();
        $usuario_exixst_app = UsuarioApp::where("email", $dados['emailSolicitante'])->where("tipo", "sindico")->get()->first();

        if ($usuario_exixst_app && !(Hash::check($dados['senha'], $usuario_exixst_app->senha) || $usuario_exixst_app->senha == md5($dados['senha']) || $usuario_exixst_app->senha == $dados['senha'])) {
            return $this->successResponse('Sucesso!', "Credenciais inválidas. Insira sua senha fornecida anteriormente.");
        }

        if ($usuario_exixst_app && (Hash::check($dados['senha'], $usuario_exixst_app->senha) || $usuario_exixst_app->senha == md5($dados['senha']) || $usuario_exixst_app->senha == $dados['senha'])) {
            $usuario_app = $usuario_exixst_app;
            $sindico = ModelsSindico::where("usuario_app_id", $usuario_app->id)->first();
        } else {
            //Novo usuário
            $usuario_app = new UsuarioApp();
            $usuario_app->email = $dados['emailSolicitante'];
            $usuario_app->senha = Hash::make($dados['senha']);
            $usuario_app->tipo = "sindico";
            $usuario_app->imagem = "no-image-perfil.png";
            $usuario_app->isFacebook = 0;
            $usuario_app->isEmail = 1;
            $usuario_app->save();

            //Novo sindico
            $sindico = new ModelsSindico();
            $sindico->nome = $dados['nomeSolicitante'];
            $sindico->forma_cadastro = "Site";
            $sindico->telefone = $dados['telefoneSolicitante'];
            $sindico->usuario_app_id = $usuario_app->id;
            $sindico->save();
        }


        if ($dados['condominio_id'] > 0) {
            $condominio = Condominio::where("id", $dados['condominio_id'])->where("sindico_id", $sindico->id)->first();
            if ($condominio == null) {
                return $this->successResponse('Sucesso!', "Condomínio não existe.");
            }
            $this->updateCondminioBairro($condominio);
        } else {
            //Novo condominio
            $condominio = new Condominio();
            $condominio->nome = $dados['nomeCondominio'];
            $condominio->cep = $dados['cep'];
            $condominio->bairro = $dados['bairro'];
            $condominio->endereco = $dados['endereco'];
            $condominio->numero = $dados['numero'];
            $condominio->complemento = $dados['complemento'];
            $e = Estado::where("id", $dados['estado'])->first();
            $condominio->estado = $e->uf;

            $cid = Cidade::where("id", $dados['cidade'])->first();
            $condominio->cidade = $cid->nome;

            $condominio->sindico_id = $sindico->id;


            //INICIO - Adicionando bairro_id no condominio
            $bairros = Bairro::where("chave", "LIKE", "%" . Formatacao::chave($condominio->bairro) . "%")->get();
            $encontrouBairro = false;
            foreach ($bairros as $bairroLinha) {
                $cid = Cidade::where("id", $bairroLinha->cidade_id)->first();
                $estado = Estado::where("id", $cid->estado_id)->first();
                if ((strtoupper($estado->uf) == strtoupper($condominio->estado) || Formatacao::chave($estado->nome) == Formatacao::chave($condominio->estado)) && Formatacao::chave($cid->nome) == Formatacao::chave($condominio->cidade)) {
                    $condominio->estado = $estado->uf;
                    $condominio->cidade = $cid->nome;
                    $condominio->bairro = $bairroLinha->nome;
                    $encontrouBairro = true;
                    break;
                }
            }

            if ($encontrouBairro == false) {
                $cidadeReq = Cidade::where("chave", "LIKE", "%" . Formatacao::chave($condominio->cidade) . "%")->where("uf", "LIKE", $condominio->uf)->first();

                if (!$cidadeReq) {
                    $est = Estado::where("uf", $condominio->estado)->first();
                    if (!$est) {
                        return $this->errorResponse([array("error_code" => "invalid-uf", "error_message" => "Estado não encontrado")], 403);
                    }
                    $cidadeReq = new Cidade();
                    $cidadeReq->nome = $condominio->cidade;
                    $cidadeReq->uf = $condominio->estado;
                    $cidadeReq->estado_id = $est->id;
                    $cidadeReq->save();
                }

                $bairro = new Bairro();
                $bairro->nome = $condominio->bairro;
                $bairro->cidade_id = $cidadeReq->id;
                $bairro->chave = Formatacao::chave($bairro->nome);
                $bairro->save();
                $condominio->bairro_id = $bairro->id;
            } else {
                $condominio->bairro_id = $bairroLinha->id;
            }
            //FIM - Adicionando bairro_id no condominio
            $condominio->save();

            $this->updateCondminioBairro($condominio);
        }


        try {
            //Nova solicitação
            $orcamento = new Orcamento();
            $orcamento->nome = $dados['tipoServico'];
            $orcamento->descricao = $dados['detalhesSolicitacao'];
            $orcamento->categoria_id = $dados['categoria_id'];
            $orcamento->condominio_id = $condominio->id;
            $orcamento->urgente = 0;
            $orcamento->status = StatusOrcamento::$ANALISANDO_CANDIDATOS;
            $orcamento->status_sindico = StatusOrcamento::$ANALISANDO_CANDIDATOS;
            $orcamento->status_afiliado = StatusOrcamento::$ANALISANDO_CANDIDATOS;
            $orcamento->modo = Util::getModusOperandi();
            $orcamento->formato_contrato_atual = 4;
            $validacao = OrcamentoBO::validarOrcamento($orcamento);

            if ($validacao->verifica()) {
                //Verifica qual é a região atual do bairro onde o condomimío está localizado
                $bairro_condominio = Bairro::where("id", $condominio->bairro_id)->first();
                $regiao_id_bairro_condominio = $bairro_condominio->regiao_id;

                if (!($regiao_id_bairro_condominio > 0)) {
                    $cidadesRegiao = RegiaoFaixaCep::where("cidade_id", $bairro_condominio->cidade_id)->first();
                    if ($cidadesRegiao) {
                        $regiao_id_bairro_condominio = $cidadesRegiao->regiao_id;
                    }
                }

                if (!($regiao_id_bairro_condominio > 0)) {
                    $orcamento->condominio->cep = str_replace("-", "", $orcamento->condominio->cep);
                    $cep = $orcamento->condominio->cep;
                    $cont = 0;
                    do {
                        $cont++;
                        $regiaoFaixaCep = RegiaoFaixaCep::where("cep", "LIKE", $cep)->orderBy("id", "desc")->first();
                        $cep = substr($cep, 0, strlen($orcamento->condominio->cep) - $cont);
                        if ($cep == "" || strlen($cep) <= 5 || $cont == 6) {
                            break;
                        }
                    } while (!$regiaoFaixaCep);

                    if ($regiaoFaixaCep) {
                        $regiao_id_bairro_condominio = $regiaoFaixaCep->regiao_id;
                    }
                }

                if (!($regiao_id_bairro_condominio > 0)) {
                    $regiao_id_bairro_condominio = 12; //Solicitações onde não foi encontrada uma região específica
                }

                $orcamento->regiao_id = $regiao_id_bairro_condominio;
                $orcamento->save();
            }

            DB::commit();


            //Criar uma vistoria se for tipo gratuita
            // $vistoria = new Vistoria();
            // $vistoria->orcamento_id = $orcamento->id;
            // $vistoria->descricao = "Vistoria de categoria gratuita";
            // $vistoria->forma_cadastro = "Via site";
            // $vistoria->save();

            //Envio das notificações
            //$this->enviarNotificarAfiliadosFranqueados($orcamento->categoria_id, $orcamento->regiao_id, $orcamento->id, $condominio->nome);
            SenderEmails::boasVindasSindico($this, $usuario_app->email, $sindico->nome, $dados['senha'], true, $usuario_app->id);

            return $this->successResponse('Sucesso!', "true");
        } catch (Exception $e) {
            DB::rollback();
            return $this->successResponse('Sucesso!', $e->getMessage());
        }
    }



    public function updateCondminioBairro($condominio)
    {
        $est = Estado::where("uf", "like", $condominio->estado, "or")->where("nome", "like", $condominio->estado, "or")->first();
        if (!$est) {
            return $this->errorResponse([array("error_code" => "invalid-uf", "error_message" => "Estado não encontrado")], 403);
        }

        $bairros = Bairro::where("chave", "LIKE", "%" . Formatacao::chave($condominio->bairro) . "%")->orderBy("id", "asc")->get();

        $encontrouBairro = false;
        foreach ($bairros as $bairroLinha) {
            $cid = Cidade::where("id", $bairroLinha->cidade_id)->first();
            $estado = Estado::where("id", $cid->estado_id)->first();
            if ((strtoupper($estado->uf) == strtoupper($condominio->estado) || Formatacao::chave($estado->nome) == Formatacao::chave($condominio->estado)) && Formatacao::chave($cid->nome) == Formatacao::chave($condominio->cidade)) {
                $condominio->estado = $estado->uf;
                $condominio->cidade = $cid->nome;
                $condominio->bairro = $bairroLinha->nome;
                $encontrouBairro = true;
                break;
            }
        }

        if ($encontrouBairro == false) {
            $cidadeReq = Cidade::where("chave", "LIKE", "%" . Formatacao::chave($condominio->cidade) . "%")->where("uf", "LIKE", $condominio->estado)->first();

            if (!$cidadeReq) {
                //return $this->errorResponse([array("error_code" => "invalid-cidade", "error_message" => "Não encontramos sua cidade. Fale com a administração.")], 403);

                $est = Estado::where("uf", "like", $condominio->estado, "or")->where("nome", "like", $condominio->estado, "or")->first();
                if (!$est) {
                    return $this->errorResponse([array("error_code" => "invalid-uf", "error_message" => "Estado não encontrado")], 403);
                }
                $cidadeReq = new Cidade();
                $cidadeReq->nome = $condominio->cidade;
                $cidadeReq->uf = $condominio->estado;
                $cidadeReq->estado_id = $est->id;
                $cidadeReq->save();
            }

            $bairro = new Bairro();
            $bairro->nome = $condominio->bairro;
            $bairro->cidade_id = $cidadeReq->id;
            $bairro->chave = Formatacao::chave($bairro->nome);
            $bairro->save();
            $condominio->bairro_id = $bairro->id;
        } else {
            $condominio->bairro_id = $bairroLinha->id;
        }

        $condominio->update();
    }


    public function enviarNotificarAfiliadosFranqueados($categoria_id, $regiao_id, $orcamento_id, $nome_codominio)
    {
        if ($orcamento_id > 0) {
            $franqueado_regiao = FranqueadoRegiao::where("regiao_id", $regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
            $franqueado = Franqueado::where("id", $franqueado_regiao->franqueado_id)->first();
            if ($franqueado) {
                SenderEmails::enviarEmailFranqueado($franqueado->email, $franqueado->nome, $orcamento_id, "Site");
                SenderEmails::enviarEmailSuperAdmin("renato.paranagua@hotmail.com", $franqueado->nome, $orcamento_id, "Site");
                if (getenv('APP_DEBUG') == false) {
                    SenderEmails::enviarEmailSuperAdmin("contato@casadosindico.srv.br", $franqueado->nome, $orcamento_id, "Site");
                    SenderEmails::enviarEmailSuperAdmin("adm@casadosindico.srv.br", $franqueado->nome, $orcamento_id, "Site");
                }
            } else {
                SenderEmails::enviarEmailSuperAdmin("renato.paranagua@hotmail.com", "SEM FRANQUIA", $orcamento_id, "Site");
                if (getenv('APP_DEBUG') == false) {
                    SenderEmails::enviarEmailSuperAdmin("contato@casadosindico.srv.br",  "SEM FRANQUIA", $orcamento_id, "Site");
                    SenderEmails::enviarEmailSuperAdmin("adm@casadosindico.srv.br",  "SEM FRANQUIA", $orcamento_id, "Site");
                }
            }
        }

        $afiliadosEnviar = [];
        $afiliadosCategorias = AfiliadoCategorium::where("categoria_id", $categoria_id)->where("status", "aprovado")->get();
        foreach ($afiliadosCategorias as $afiliadoCat) {
            $afiliadosRegiaoLista = AfiliadoRegiao::where("regiao_id", $regiao_id)->where("afiliado_id", $afiliadoCat->afiliado_id)->get();

            foreach ($afiliadosRegiaoLista as $afiliadosRegiao) {
                if ($afiliadosRegiao) {
                    $planoRegiao = PlanoAssinaturaAfiliadoRegiao::where("id", $afiliadosRegiao->plano_assinatura_afiliado_regiao_id)->where("statusPlano", 1)->orderBy("id", "desc")->first();
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


                        if ($planoRegiao->tipo_assinatura == 1 && $planoRegiao->status_afiliado == 1) {
                            //Altenticado pelo autentique
                            $autorizeAutentique = true;
                        } else if ($planoRegiao->tipo_assinatura == 2) {
                            //Autenticado pela franquia
                            $autorizeAutentique = true;
                        }

                        $afiliado = Afiliado::where("id", $afiliadosRegiao->afiliado_id)->first();
                        $usuarioApp = UsuarioApp::where("id", $afiliado->usuario_app_id)->first();
                        //Verifica se está autorizado a ver este orçamento
                        if ($autorizeAsaas && $autorizeAutentique && $usuarioApp && $usuarioApp->data_confirmacao) {
                            $afiliadosEnviar[] = $afiliado;
                        }
                    }
                }
            }
        }

        foreach ($afiliadosEnviar as $afil) {
            if ($afil->email) {
                SenderEmails::enviarEmailAfiliadosNovaSolicitacao($afil->email, $afil->razao_social, $orcamento_id);
            }

            $usuarioApp = UsuarioApp::where("id", $afil->usuario_app_id)->first();
            if ($usuarioApp && $usuarioApp->token_notification) {
                SenderNotificacao::enviarNotificacaoNovaSolicitacao($orcamento_id, $usuarioApp->token_notification, $nome_codominio);
            }

            if ($usuarioApp && $afil->email != $usuarioApp->email) {
                SenderEmails::enviarEmailAfiliadosNovaSolicitacao($usuarioApp->email, $afil->razao_social, $orcamento_id);
            }
        }
    }
}
