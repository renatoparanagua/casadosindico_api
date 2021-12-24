<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\PlanoDisponivelFranqueado as ResourcesPlanoDisponivelFranqueado;
use App\Models\FranqueadoRegiao;
use App\Models\FranqueadoRegiaoPlanoDisponibilizado;
use App\Models\PlanoDisponivelFranqueado;
use Exception;
use Illuminate\Http\Request;

class PlanoDisponivelFranqueadoController extends Controller
{

    public function __construct(Request $request)
    {
        parent::__construct($request, null, new PlanoDisponivelFranqueado());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $planos_disponiveis_franqueado = PlanoDisponivelFranqueado::all();
            $data = new ResourcesPlanoDisponivelFranqueado($planos_disponiveis_franqueado);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function planosByRegiao($regiao_id)
    {

        try {
            $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
            $plano_disponivel_franqueado = [];

            if ($franqueadoRegiao) {
                $franqueado_regiao_plano_disponibilizado = FranqueadoRegiaoPlanoDisponibilizado::where("franqueado_regiao_id", $franqueadoRegiao->id)->get();
                foreach ($franqueado_regiao_plano_disponibilizado as $linha) {
                    $r = PlanoDisponivelFranqueado::where("id", $linha['plano_disponivel_franqueado_id'])->where("statusPlano", 1)->where("is_public", 1)->orderBy("id", "desc")->first();
                    if ($r) {
                        $plano_disponivel_franqueado[] = $r;
                    }
                }
            }
            return $this->successResponse('Success', $plano_disponivel_franqueado);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
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
        try {
            $plano_disponivel_franqueado = new PlanoDisponivelFranqueado();
            $plano_disponivel_franqueado->nome = $request['nome'];
            $plano_disponivel_franqueado->descricao = $request['descricao'];
            $plano_disponivel_franqueado->valor = $request['valor'];
            $plano_disponivel_franqueado->valor_comissao = $request['valor_comissao'];
            $plano_disponivel_franqueado->dias_trial = $request['dias_trial'];
            $plano_disponivel_franqueado->regiao_id = $request['regiao_id'];
            $plano_disponivel_franqueado->quantidade_meses_vigencia = Asaas::getMesesCiclo($request['ciclo']);
            $plano_disponivel_franqueado->usuario_sistema_admin_id = $request['usuario_sistema_admin_id'];
            $plano_disponivel_franqueado->statusPlano = $request['statusPlano'];
            $plano_disponivel_franqueado->ciclo = $request['ciclo'];
            $plano_disponivel_franqueado->save();
            $data = new ResourcesPlanoDisponivelFranqueado($plano_disponivel_franqueado);
            return $this->successResponse('Plano disponivel franqueado created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Model\PlanoDisponivelFranqueado  $planoDisponivelFranqueado
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        try {
            $plano_disponivel_franqueado = PlanoDisponivelFranqueado::findOrFail($id);
            $data = new ResourcesPlanoDisponivelFranqueado($plano_disponivel_franqueado);
            return $this->successResponse('Success', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\PlanoDisponivelFranqueado  $planoDisponivelFranqueado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $plano_disponivel_franqueado = PlanoDisponivelFranqueado::findOrFail($id);
            $plano_disponivel_franqueado->nome = $request['nome'];
            $plano_disponivel_franqueado->descricao = $request['descricao'];
            $plano_disponivel_franqueado->valor = $request['valor'];
            $plano_disponivel_franqueado->valor_comissao = $request['valor_comissao'];
            $plano_disponivel_franqueado->dias_trial = $request['dias_trial'];
            $plano_disponivel_franqueado->regiao_id = $request['regiao_id'];
            $plano_disponivel_franqueado->quantidade_meses_vigencia = Asaas::getMesesCiclo($request['ciclo']);
            $plano_disponivel_franqueado->usuario_sistema_admin_id = $request['usuario_sistema_admin_id'];
            $plano_disponivel_franqueado->statusPlano = $request['statusPlano'];
            $plano_disponivel_franqueado->ciclo = $request['ciclo'];
            $plano_disponivel_franqueado->update();
            $data = new ResourcesPlanoDisponivelFranqueado($plano_disponivel_franqueado);
            return $this->successResponse('Plano disponivel franqueado updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Model\PlanoDisponivelFranqueado  $planoDisponivelFranqueado
     * @return \Illuminate\Http\Response
     */
    public function destroy($id = null)
    {
        try {
            $plano_disponivel_franqueado = PlanoDisponivelFranqueado::findOrFail($id);
            $plano_disponivel_franqueado->delete();
            $data = new ResourcesPlanoDisponivelFranqueado($plano_disponivel_franqueado);
            return $this->successResponse('Plano disponivel franqueado deleted!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }
}
