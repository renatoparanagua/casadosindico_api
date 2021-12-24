<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Sindico as ResourcesSindico;
use App\Models\AfiliadoOrcamentoInteresse;
use App\Models\BO\SindicoBO;
use App\Models\Condominio;
use App\Models\DAO\SindicoDAO;
use App\Models\LogSystem;
use App\Models\Notificacao;
use App\Models\Sindico;
use App\Models\Usuario;
use App\Models\UsuarioApp;
use App\Util\Validacao;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SindicoController extends Controller
{
    public function __construct(Request $request)
    {
        $class_name = new Sindico();
        parent::__construct($request, $class_name);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {
        $this->newLog("Síndico solicitando cadastro do perfil.");
        DB::beginTransaction();
        try {
            $dados = SindicoBO::getDataOnlyName($request);
            $dados["forma_cadastro"] = "Via App";
            $dados["usuario_app_id"] = $this->usuario_logado->id;
            $dados["telefone"] = isset($request['telefone']) ? $request['telefone'] : null;

            $sindico = SindicoDAO::cadastrar($dados);
            Notificacao::painelNotificarUsuarioBoasVindas($request['nome'], "sindico", $dados["usuario_app_id"]);
            DB::commit();
            return $this->successResponse('Cadastrado realizado com sucesso.', SindicoBO::transform($sindico));
        } catch (Exception $e) {
            DB::rollBack();
            UsuarioApp::where("id", $this->usuario_logado->id)->delete();
            return $this->errorResponse($e, 403);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Sindico  $sindico
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {
        $this->newLog("Síndico solicitando atualização do perfil.");
        try {
            $sindico = $this->class_name::where($this->column_name, $this->usuario_logado->id)->findOrFail($this->usuario_tipo_id);
            $sindico->nome = $this->getValueRequest($request, $sindico, 'nome');
            $sindico->numero_documento = $this->getValueRequest($request, $sindico, 'numero_documento');
            $sindico->CPF = $this->getValueRequest($request, $sindico, 'CPF');
            $sindico->telefone = $this->getValueRequest($request, $sindico, 'telefone');

            $sindicoAux = Sindico::where("CPF", $sindico->CPF)->where("CPF", "<>", null)->where("CPF", "<>", "")->first();
            if ($sindicoAux && $sindico->id != $sindicoAux->id) {
                return $this->errorResponse([["error_code" => "exists-cpf", "error_message" => "Este CPF já está cadastrado. Informe seu CPF ou entre em contato com a central."]], 403);
            }

            $validacao = SindicoBO::validarSindico($sindico);
            if ($validacao->verifica()) {
                $sindico->update();
                $data = new ResourcesSindico($sindico);
                return $this->successResponse('Perfil atualizado :)', $data);
            } else {
                return $this->errorResponse($validacao->getErros(), 403);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getCode());
        }
    }


    public function partial_update(Request $request)
    {
        $this->newLog("Síndico solicitando atualização do perfil.");

        try {
            $sindico = Sindico::where("usuario_app_id", $this->usuario_logado->id)->first();
            if ($sindico) {
                $sindico->nome = $this->getValueRequest($request, $sindico, 'nome', true);
                $sindico->numero_documento = $this->getValueRequest($request, $sindico, 'numero_documento', true);
                $sindico->CPF = $this->getValueRequest($request, $sindico, 'CPF', true);
                $sindico->telefone = $this->getValueRequest($request, $sindico, 'telefone', true);

                $sindicos = Sindico::pluck("id", "CPF");
                if ($sindico->CPF && isset($sindicos[$sindico->CPF])) {
                    $sindicoId = $sindicos[$sindico->CPF];
                    $sindico = Sindico::where("id", $sindicoId)->first();

                    $userSindico = null;
                    if ($sindico && $sindico->usuario_app_id > 0) {
                        $userSindico = UsuarioApp::where("id", $sindico->usuario_app_id)->first();
                    }

                    if ($sindicoId != $this->usuario_tipo_id && $userSindico != null) {
                        return $this->errorResponse([["error_code" => "exists-cpf", "error_message" => "Este CPF já está cadastrado. Informe seu CPF ou entre em contato com a central."]], 403);
                    }
                }

                $validacao = SindicoBO::validarSindicoSoft($sindico);
                if ($validacao->verifica()) {
                    $sindico->update();
                    return $this->successResponse('Perfil atualizado :)', $sindico);
                } else {
                    return $this->errorResponse($validacao->getErros(), 403);
                }
            } else {
                return $this->errorResponse([Validacao::getError("Seu usuário foi deletado. Contate os administradores.", 'deleted')], 403);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
        }
    }


    public function pendencias()
    {
        $this->newLog("Síndico listando pendências");
        try {

            if ($this->usuario_logado == null) {
                return $this->errorResponse([Validacao::getError("Não foi possível carregar as pendências.", "inexistente")], 403);
            }

            $sindico = Sindico::where("usuario_app_id", $this->usuario_logado->id)->first();

            if ($sindico == null) {
                return $this->errorResponse([Validacao::getError("Não foi possível carregar as pendências.", "inexistente")], 403);
            }

            $resCondominio = Condominio::where("sindico_id", $sindico->id)->count();
            $pendencias['condominio'] = $resCondominio == 0;
            $pendencias['conta'] = $this->usuario_logado->data_confirmacao == null || $this->usuario_logado->data_confirmacao == "";
            $pendencias['cpf'] = $sindico->CPF == "";
            $pendencias['telefone'] = $sindico->telefone == "";

            $cont = 0;
            foreach ($pendencias as $p) {
                if ($p == true) $cont++;
            }
            $pendencias['total'] = $cont;
            return $this->successResponse('Success', $pendencias);
        } catch (Exception $e) {
            return $this->errorResponse([Validacao::getError("Erro ao carregar pendências.", "login")], 403);
        }
    }

    // public function afiliadoInteresse($orcamento_id)
    // {
    //     $this->newLog("Síndico solicitando afiliados interessados em um orçamento.");
    //     try {
    //         $afiliados = AfiliadoOrcamentoInteresse::join('orcamento', 'orcamento.id', 'afiliado_orcamento_interesse.orcamento_id')
    //             ->join('condominio', 'condominio.id', 'orcamento.condominio_id')
    //             ->join('afiliado', 'afiliado.id', 'afiliado_orcamento_interesse.afiliado_id')
    //             ->where('condominio.sindico_id', $this->usuario_tipo_id)
    //             ->where('afiliado_orcamento_interesse.orcamento_id', $orcamento_id)
    //             ->where('afiliado_orcamento_interesse.nao_interessante', 0)
    //             ->where('afiliado_orcamento_interesse.interessado', 1)
    //             ->select('afiliado.*')
    //             ->distinct()
    //             ->get();
    //         return $this->successResponse('Success', $afiliados);
    //     } catch (Exception $e) {
    //         return $this->errorResponse('Error processing your request');
    //     }
    // }

}
