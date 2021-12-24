<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Condominio as ResourcesCondominio;
use App\Models\Bairro;
use App\Models\BO\CondominioBO;
use App\Models\Cidade;
use App\Models\Condominio;
use App\Models\Estado;
use App\Models\Regiao;
use App\Models\RegiaoFaixaCep;
use App\Models\Sindico;
use App\Util\Formatacao as UtilFormatacao;
use Exception;
use Formatacao;
use Illuminate\Http\Request;

class CondominioController extends Controller
{
    public function __construct(Request $request)
    {
        $class_name = new Condominio();
        parent::__construct($request, $class_name, new Sindico());
    }

    public function index()
    {
        $this->newLog("Listando condomínios");
        try {
            $condominios = Condominio::where("sindico_id", $this->usuario_tipo_id)->where("status", "ativo")->orderBy("nome")->get();
            foreach ($condominios as $key => $condominio) {
                $regiao_id = $this->getRegiaoByCondominio($condominio);
                $regiao = Regiao::where("id", $regiao_id)->first();
                if ($regiao) {
                    $condominios[$key]->regiao_nome = " Região: " . $regiao->nome;
                    $condominios[$key]->regiao_color = "success";
                } else {
                    $condominios[$key]->regiao_nome = "SEM REGIÃO";
                    $condominios[$key]->regiao_color = "danger";
                }
            }
            return $this->successResponse('Success', $condominios);
        } catch (Exception $e) {
            return $this->errorResponse('Erro ao listar', 403);
        }
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $this->newLog("Síndico cadastrando condomínio");
        try {
            $condominio = new Condominio();
            $condominio->nome = $this->getValueRequest($request, $condominio, 'nome');
            $condominio->cep = $this->getValueRequest($request, $condominio, 'cep');
            $condominio->cnpj = $this->getValueRequest($request, $condominio, 'cnpj');
            $condominio->bairro = $this->getValueRequest($request, $condominio, 'bairro');
            $condominio->endereco = $this->getValueRequest($request, $condominio, 'endereco');
            $condominio->numero = $this->getValueRequest($request, $condominio, 'numero');
            $condominio->complemento = $this->getValueRequest($request, $condominio, 'complemento');
            $condominio->estado = strtoupper($this->getValueRequest($request, $condominio, 'estado'));
            $condominio->cidade = $this->getValueRequest($request, $condominio, 'cidade');

            $validacao = CondominioBO::validarCondominio($condominio);
            if (!$validacao->verifica()) {
                return $this->errorResponse($validacao->getErros(), 403);
            }

            $est = Estado::where("uf", "like", $condominio->estado, "or")->where("nome", "like", $condominio->estado, "or")->first();

            if (!$est) {
                return $this->errorResponse([array("error_code" => "invalid-uf", "error_message" => "Estado não encontrado")], 403);
            }
            $bairros = Bairro::where("chave", "LIKE", "%" . UtilFormatacao::chave($condominio->bairro) . "%")->orderBy("id", "asc")->get();

            $encontrouBairro = false;
            foreach ($bairros as $bairroLinha) {
                $cid = Cidade::where("id", $bairroLinha->cidade_id)->first();
                $estado = Estado::where("id", $cid->estado_id)->first();
                if ((strtoupper($estado->uf) == strtoupper($condominio->estado) || UtilFormatacao::chave($estado->nome) == UtilFormatacao::chave($condominio->estado)) && UtilFormatacao::chave($cid->nome) == UtilFormatacao::chave($condominio->cidade)) {
                    $condominio->estado = $estado->uf;
                    $condominio->cidade = $cid->nome;
                    $condominio->bairro = $bairroLinha->nome;
                    $encontrouBairro = true;
                    break;
                }
            }

            if ($encontrouBairro == false) {
                $cidadeReq = Cidade::where("chave", "LIKE", "%" . UtilFormatacao::chave($condominio->cidade) . "%")->where("uf", "LIKE", $condominio->estado)->first();

                if (!$cidadeReq) {
                    return $this->errorResponse([array("error_code" => "invalid-cidade", "error_message" => "Não encontramos sua cidade. Fale com a administração.")], 403);
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
                $bairro->chave = UtilFormatacao::chave($bairro->nome);
                $bairro->save();
                $condominio->bairro_id = $bairro->id;
            } else {
                $condominio->bairro_id = $bairroLinha->id;
            }


            $condominio->sindico_id = $this->usuario_tipo_id;

            $validacao = CondominioBO::validarCondominio($condominio);
            if ($validacao->verifica()) {
                $condominio->save();
                return $this->successResponse('Condomínio criado com sucesso.', $condominio);
            } else {
                return $this->errorResponse($validacao->getErros(), 403);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }

    public function getRegiaoByCondominio($condominio)
    {
        $bairro_condominio = Bairro::where("id", $condominio->bairro_id)->first();

        if ($bairro_condominio == null) {
            return null;
        }

        $regiao_id_bairro_condominio = $bairro_condominio->regiao_id;

        if (!($regiao_id_bairro_condominio > 0)) {
            $cidadesRegiao = RegiaoFaixaCep::where("cidade_id", $bairro_condominio->cidade_id)->first();
            if ($cidadesRegiao) {
                $regiao_id_bairro_condominio = $cidadesRegiao->regiao_id;
            }
        }

        if (!($regiao_id_bairro_condominio > 0)) {
            $condominio->cep = str_replace("-", "", $condominio->cep);
            $cep = $condominio->cep;
            $cont = 0;
            do {
                $cont++;
                $regiaoFaixaCep = RegiaoFaixaCep::where("cep", "LIKE", $cep)->orderBy("id", "desc")->first();
                $cep = substr($cep, 0, strlen($condominio->cep) - $cont);
                if ($cep == "" || strlen($cep) <= 4 || $cont == 6) {
                    break;
                }
            } while (!$regiaoFaixaCep);

            if ($regiaoFaixaCep) {
                $regiao_id_bairro_condominio = $regiaoFaixaCep->regiao_id;
            }
        }

        return $regiao_id_bairro_condominio;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Condominio  $condominio
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $this->newLog("Síndico atualizando condomínio");
        try {
            $condominio = Condominio::where("sindico_id", $this->usuario_tipo_id)->findOrFail($id);

            if ($condominio) {
                $condominio->nome = $this->getValueRequest($request, $condominio, 'nome');
                $condominio->cnpj = $this->getValueRequest($request, $condominio, 'cnpj');
                $condominio->cep = $this->getValueRequest($request, $condominio, 'cep');
                $condominio->bairro = $this->getValueRequest($request, $condominio, 'bairro');
                $condominio->endereco = $this->getValueRequest($request, $condominio, 'endereco');
                $condominio->numero = $this->getValueRequest($request, $condominio, 'numero');
                $condominio->complemento = $this->getValueRequest($request, $condominio, 'complemento');
                $condominio->estado = strtoupper($this->getValueRequest($request, $condominio, 'estado'));
                $condominio->cidade = $this->getValueRequest($request, $condominio, 'cidade');

                $validacao = CondominioBO::validarCondominio($condominio);
                if (!$validacao->verifica()) {
                    return $this->errorResponse($validacao->getErros(), 403);
                }
                $est = Estado::where("uf", "like", $condominio->estado, "or")->where("nome", "like", $condominio->estado, "or")->first();
                if (!$est) {
                    return $this->errorResponse([array("error_code" => "invalid-uf", "error_message" => "Estado não encontrado")], 403);
                }

                $bairros = Bairro::where("chave", "LIKE", "%" . UtilFormatacao::chave($condominio->bairro) . "%")->orderBy("id", "asc")->get();

                $encontrouBairro = false;
                foreach ($bairros as $bairroLinha) {
                    $cid = Cidade::where("id", $bairroLinha->cidade_id)->first();
                    $estado = Estado::where("id", $cid->estado_id)->first();
                    if (($estado->uf == $condominio->estado || $estado->nome == $condominio->estado) && UtilFormatacao::chave($cid->nome) == UtilFormatacao::chave($condominio->cidade)) {
                        $condominio->estado = $estado->uf;
                        $condominio->cidade = $cid->nome;
                        $condominio->bairro = $bairroLinha->nome;
                        $encontrouBairro = true;
                        break;
                    }
                }

                if ($encontrouBairro == false) {
                    $cidadeReq = Cidade::where("chave", "LIKE", "%" . UtilFormatacao::chave($condominio->cidade) . "%")->where("uf", "LIKE", $condominio->estado)->first();

                    if (!$cidadeReq) {
                        return $this->errorResponse([array("error_code" => "invalid-cidade", "error_message" => "Não encontramos sua cidade. Fale com a administração.")], 403);

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
                    $bairro->chave = UtilFormatacao::chave($bairro->nome);
                    $bairro->save();
                    $condominio->bairro_id = $bairro->id;
                } else {
                    $condominio->bairro_id = $bairroLinha->id;
                }


                if ($validacao->verifica()) {
                    $condominio->update();
                    return $this->successResponse('Condomínio atualizado.', $condominio);
                } else {
                    return $this->errorResponse($validacao->getErros(), 403);
                }
            } else {
                return $this->errorResponse([array("error_code" => "condominio-removido", "error_message" => "O condomínio foi removido. Contate os administradores.")], 403);
            }
        } catch (Exception $e) {
            return $this->errorResponse('Error processing you request');
        }
    }

    public function arquivar($id)
    {
        $this->newLog("Síndico arquivou condomínio");
        try {
            $condominio = Condominio::where("sindico_id", $this->usuario_tipo_id)->findOrFail($id);
            $condominio->status = "inativo";
            $condominio->update();
            return $this->successResponse('Condomínio arquivado.', $condominio);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing you request');
        }
    }
}
