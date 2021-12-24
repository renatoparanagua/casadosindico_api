<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Afiliado as ResourcesAfiliado;
use App\Models\Afiliado;
use App\Models\BO\AfiliadoBO;
use App\Model\ResponsavelAfiliado;
use App\Models\AfiliadoRegiao;
use App\Models\AfiliadoCategorium;
use App\Models\AfiliadoFranqueadoAsaas;
use App\Models\CartaoCnpj;
use App\Models\Categoria;
use App\Models\ContratoSocial;
use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\Notificacao;
use App\Models\ResponsavelAfiliado as ModelsResponsavelAfiliado;
use App\Models\UsuarioApp;
use App\Util\StatusAsass;
use App\Util\Util;
use App\Util\Validacao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AfiliadoController extends Controller
{

    public function __construct(Request $request)
    {
        $class_name = new Afiliado();
        parent::__construct($request, $class_name);
    }

    public function store(Request $request)
    {
        $this->newLog("Novo afiliado");
        DB::beginTransaction();
        try {
            $afiliado = new Afiliado();
            $afiliado->email = trim($request['email']);
            $afiliado->usuario_app_id = $this->usuario_logado->id;
            $afiliado->forma_cadastro = "Via app";
            $afiliado->telefone = isset($request['telefone']) ? $request['telefone'] : null;
            $afiliado->status = "ativo";
            $afiliado->save();

            $responsavelAfiliado = new ModelsResponsavelAfiliado();
            $responsavelAfiliado->afiliado_id = $afiliado->id;
            $responsavelAfiliado->nome = $request['nome'];
            $responsavelAfiliado->email = trim($afiliado->email);
            $responsavelAfiliado->telefone = isset($request['telefone']) ? $request['telefone'] : null;
            $responsavelAfiliado->save();
            $data = new ResourcesAfiliado($afiliado);
            DB::commit();

            Notificacao::painelNotificarUsuarioBoasVindas($request['nome'], "afiliado", $afiliado->usuario_app_id);
            return $this->successResponse('Cadastrado realizado com sucesso.', $data);
        } catch (Exception $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Afiliado  $afiliado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->newLog("Atualizando perfil afiliado");
        try {
            $afiliado = Afiliado::findOrFail($this->usuario_tipo_id);
            if ($afiliado) {
                $afiliado->razao_social = $this->getValueRequest($request, $afiliado, 'razao_social');
                $afiliado->nome_fantasia = $this->getValueRequest($request, $afiliado, 'nome_fantasia');
                $afiliado->email = trim($this->getValueRequest($request, $afiliado, 'email'));
                $afiliado->telefone = $this->getValueRequest($request, $afiliado, 'telefone');
                $afiliado->cnpj = $this->getValueRequest($request, $afiliado, 'cnpj');
                $afiliado->inscricao_estadual = $this->getValueRequest($request, $afiliado, 'inscricao_estadual');
                $afiliado->inscricao_municipal = $this->getValueRequest($request, $afiliado, 'inscricao_municipal');
                $afiliado->cep = $this->getValueRequest($request, $afiliado, 'cep');
                $afiliado->estado = $this->getValueRequest($request, $afiliado, 'estado');
                $afiliado->cidade = $this->getValueRequest($request, $afiliado, 'cidade');
                $afiliado->bairro = $this->getValueRequest($request, $afiliado, 'bairro');
                $afiliado->numero = $this->getValueRequest($request, $afiliado, 'numero');
                $afiliado->rua = $this->getValueRequest($request, $afiliado, 'rua');
                $afiliado->complemento = $this->getValueRequest($request, $afiliado, 'complemento');
                $afiliado->rumo_atividade = $this->getValueRequest($request, $afiliado, 'rumo_atividade');
                $afiliado->numero_funcionarios = abs($this->getValueRequest($request, $afiliado, 'numero_funcionarios'));
                $afiliado->logo = $this->getValueRequest($request, $afiliado, 'logo');

                // $afiliadoAux = Afiliado::where("cnpj", $afiliado->cnpj)->where("cnpj", "<>", null)->where("cnpj", "<>", "")->first();
                // if ($afiliadoAux && $afiliado->id != $afiliadoAux->id) {
                //     return $this->errorResponse([["error_code" => "exists-cnpj", "error_message" => "Este CNPJ já está cadastrado. Informe seu CNPJ ou entre em contato com a central."]], 403);
                // }

                $validacao = AfiliadoBO::validarAfiliado($afiliado);
                if ($validacao->verifica()) {
                    $afiliado->update();
                    $data = new ResourcesAfiliado($afiliado);
                    return $this->successResponse('Perfil atualizado :)', $data);
                } else {
                    return $this->errorResponse($validacao->getErros(), 403);
                }
            } else {
                return $this->errorResponse([0 => Validacao::getError("Seu usuário foi deletado. Contate os administradores.", 'deleted')], 403);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getCode());
        }
    }


    public function partial_update(Request $request)
    {
        $this->newLog("Atualizando perfil afiliado");
        try {
            $afiliado = Afiliado::findOrFail($this->usuario_tipo_id);

            if ($afiliado) {
                $afiliado->razao_social = $this->getValueRequest($request, $afiliado, 'razao_social', true);
                $afiliado->nome_fantasia = $this->getValueRequest($request, $afiliado, 'nome_fantasia', true);
                $afiliado->email = trim($this->getValueRequest($request, $afiliado, 'email', true));
                $afiliado->telefone = $this->getValueRequest($request, $afiliado, 'telefone', true);
                $afiliado->cnpj = $this->getValueRequest($request, $afiliado, 'cnpj', true);
                $afiliado->inscricao_estadual = $this->getValueRequest($request, $afiliado, 'inscricao_estadual', true);
                $afiliado->inscricao_municipal = $this->getValueRequest($request, $afiliado, 'inscricao_municipal', true);
                $afiliado->cep = $this->getValueRequest($request, $afiliado, 'cep', true);
                $afiliado->estado = $this->getValueRequest($request, $afiliado, 'estado', true);
                $afiliado->cidade = $this->getValueRequest($request, $afiliado, 'cidade', true);
                $afiliado->bairro = $this->getValueRequest($request, $afiliado, 'bairro', true);
                $afiliado->numero = $this->getValueRequest($request, $afiliado, 'numero', true);
                $afiliado->rua = $this->getValueRequest($request, $afiliado, 'rua', true);
                $afiliado->complemento = $this->getValueRequest($request, $afiliado, 'complemento', true);
                $afiliado->rumo_atividade = $this->getValueRequest($request, $afiliado, 'rumo_atividade', true);
                $afiliado->numero_funcionarios = abs($this->getValueRequest($request, $afiliado, 'numero_funcionarios', true));
                $afiliado->logo = $this->getValueRequest($request, $afiliado, 'logo', true);

                // $afiliados = Afiliado::pluck("id", "cnpj");
                // if (isset($afiliados[$afiliado->cnpj])) {

                //     $afiliadoId = $afiliados[$afiliado->cnpj];
                //     $afiliadoAux = Afiliado::where("id", $afiliadoId)->first();

                //     $userAfiliado = null;
                //     if ($afiliadoAux->usuario_app_id) {
                //         $userAfiliado = UsuarioApp::where("id", $afiliadoAux->usuario_app_id)->first();
                //     }

                //     if ($afiliadoId != $this->usuario_tipo_id && $userAfiliado != null) {
                //         return $this->errorResponse([["error_code" => "exists-cnpj", "error_message" => "Este CNPJ já está cadastrado. Informe seu CNPJ ou entre em contato com a central pelo site casadosindico.srv.br."]], 403);
                //     }
                // }

                $validacao = AfiliadoBO::validarAfiliado($afiliado);
                if ($validacao->verifica()) {
                    $afiliado->update();
                    $data = new ResourcesAfiliado($afiliado);
                    return $this->successResponse('Perfil atualizado :)', $data);
                } else {
                    return $this->errorResponse($validacao->getErros(), 403);
                }
            } else {
                return $this->errorResponse([Validacao::getError("Seu usuário foi deletado. Contate os administradores.", 'deleted')], 403);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getCode());
        }
    }


    public function pendencias()
    {
        $this->newLog("Afiliado lista pendências");
        if ($this->usuario_logado == null) {
            return $this->errorResponse([Validacao::getError("Não foi possível carregar as pendências.", "inexistente")], 403);
        }

        $afiliado = Afiliado::where("id", $this->usuario_tipo_id)->first();

        if ($afiliado == null) {
            return $this->errorResponse([Validacao::getError("Não foi possível carregar as pendências.", "inexistente")], 403);
        }

        $pendencias['conta'] = $this->usuario_logado->data_confirmacao == null || $this->usuario_logado->data_confirmacao == "";
        $pendencias['cnpj'] = $afiliado->cnpj == "";
        $pendencias['telefone'] = $afiliado->telefone == "";
        $pendencias['categoria'] = AfiliadoCategorium::where("afiliado_id", $this->usuario_tipo_id)->where("status", "aprovado")->count() == 0;

        $pendencias['possui_regiao'] = AfiliadoRegiao::where("afiliado_id", $this->usuario_tipo_id)->count() == 0;

        $pendencias['mensagens'] = [];

        $contratoSocial = ContratoSocial::where("afiliado_id", $this->usuario_tipo_id)->whereIn("status", ["aprovado", "aceito", "pendente", "recusado"])->orderBy("id", "DESC")->first();
        if (!$contratoSocial) {
            $pendencias['mensagens'][] = "Você deve fazer upload do Contrato social e aguardar aprovação";
        } else {
            if ($contratoSocial->status == "pendente") {
                $pendencias['mensagens'][] = "Aguardando aprovação do Contrato Social";
            } elseif ($contratoSocial->status == "recusado") {
                $pendencias['mensagens'][] = "Você deve refazer upload do Contrato Social e aguardar aprovação";
            }
        }

        $cartaoCnpj = CartaoCnpj::where("afiliado_id", $this->usuario_tipo_id)->whereIn("status", ["aprovado", "aceito", "pendente", "recusado"])->orderBy("id", "DESC")->first();
        if (!$cartaoCnpj) {
            $pendencias['mensagens'][] = "Você deve fazer upload do Cartão CNPJ e aguardar aprovação";
        } else {
            if ($cartaoCnpj->status == "pendente") {
                $pendencias['mensagens'][] = "Aguardando aprovação do Cartão CNPJ";
            } elseif ($cartaoCnpj->status == "recusado") {
                $pendencias['mensagens'][] = "Você deve refazer upload do Cartão CNPJ e aguardar aprovação";
            }
        }


        $cont = 0;
        foreach ($pendencias as $p) {
            if ($p === true) $cont++;
        }
        $cont += count($pendencias['mensagens']);
        $pendencias['total'] = $cont;
        return $this->successResponse('Success', $pendencias);
    }

    public function categorias()
    {
        $this->newLog("Afiliado listou suas categorias");
        $categorias = AfiliadoCategorium::where("afiliado_id", $this->usuario_tipo_id)->get();
        $res = [];
        $cont = 0;
        foreach ($categorias as $i => $categoria) {
            $c = Categoria::where("id", $categoria->categoria_id)->first();
            if ($c) {
                if ($c->status == 1) {
                    $res[$cont] = $c;
                    $res[$cont]->status = $categoria->status;
                    $res[$cont]->motivo_reprovado = $categoria->motivo_reprovado;
                    $cont++;
                }
            }
        }
        return $this->successResponse('Success',  $res);
    }


    public function categoriasAdd(Request $request)
    {
        $this->newLog("Afiliado solicitou nova categoria");
        $categoria_id = $request["categoria_id"];

        $afiliadoCategoriaCount = AfiliadoCategorium::where("afiliado_id", $this->usuario_tipo_id)->where("categoria_id", $categoria_id)->count();

        if ($afiliadoCategoriaCount > 0) {
            return $this->errorResponse([Validacao::getError("Essa categoria já foi soicitada. Exclua ela da sua lista primeiro.", "categoria-requested")], 403);
        }

        if ($categoria_id > 0) {
            $categoriaAfiliado = new AfiliadoCategorium();
            $categoriaAfiliado->categoria_id = $categoria_id;
            $categoriaAfiliado->afiliado_id = $this->usuario_tipo_id;
            $categoriaAfiliado->status = "pendente";
            $categoriaAfiliado->save();
            return $this->successResponse('Success',  true);
        }
        return $this->errorResponse("Categoria inválida", 403);
    }

    public function categoriasRemove(Request $request)
    {
        $this->newLog("Afiliado removeu categoria");
        $categoria_id = $request["categoria_id"];

        if ($categoria_id > 0) {
            $categoriaAfiliado = AfiliadoCategorium::where("categoria_id", $categoria_id)->where("afiliado_id", $this->usuario_tipo_id)->first();
            $categoriaAfiliado->delete();
            return $this->successResponse('Success',  true);
        }
        return $this->errorResponse("Categoria inválida", 403);
    }


    public function inadimplencia()
    {
        $afiliadoRegioes = AfiliadoRegiao::where("afiliado_id", $this->usuario_tipo_id)->get();
        $cobrancasVencidasFranquias = [];
        foreach ($afiliadoRegioes as $afiliadoRegiao) {
            $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $afiliadoRegiao->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
            if ($franqueadoRegiao) {
                $franqueado = Franqueado::where("id", $franqueadoRegiao->franqueado_id)->first();
                if ($franqueado) {
                    $afiliadoFranqueadoAsaas = AfiliadoFranqueadoAsaas::where("afiliado_id", $this->usuario_tipo_id)->where("franqueado_id", $franqueado->id)->orderBy("id", "desc")->first();
                    $token_asaas_franqueado = Util::getTokenAsaasFranqueadoById($franqueado->id);

                    if ($token_asaas_franqueado && $afiliadoFranqueadoAsaas) {
                        $cobrancasVencidas = $afiliadoFranqueadoAsaas->asaas_cobrancas_vencidas ? json_decode($afiliadoFranqueadoAsaas->asaas_cobrancas_vencidas) : [];
                        if (count($cobrancasVencidas) > 0) {
                            $cobrancasVencidasFranquias[] = [
                                "franqueado" => $franqueado,
                                "cobrancas" => $cobrancasVencidas,
                                "bloqueado" => Asaas::isPossuiCobrancaVencida($cobrancasVencidas)
                            ];
                        }
                    }
                }
            }
        }
        return $this->successResponse('Success',  $cobrancasVencidasFranquias);
    }
}
