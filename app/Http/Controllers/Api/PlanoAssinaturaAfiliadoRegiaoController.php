<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\PlanoAssinaturaAfiliadoRegiao as ResourcesPlanoAssinaturaAfiliadoRegiao;
use App\Models\PlanoAssinaturaAfiliadoRegiao;
use Exception;
use Illuminate\Http\Request;

class PlanoAssinaturaAfiliadoRegiaoController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request, null, new PlanoAssinaturaAfiliadoRegiao());
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $planos_assinaturas_afiliado_regiao = PlanoAssinaturaAfiliadoRegiao::all();
            $data = new ResourcesPlanoAssinaturaAfiliadoRegiao($planos_assinaturas_afiliado_regiao);
            return $this->successResponse('Success', $data);
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
            $plano_assinatura_afiliado_regiao = new PlanoAssinaturaAfiliadoRegiao();
            $plano_assinatura_afiliado_regiao->nome = $request['nome'];
            $plano_assinatura_afiliado_regiao->descricao = $request['descricao'];
            $plano_assinatura_afiliado_regiao->valor = $request['valor'];
            $plano_assinatura_afiliado_regiao->valor_comissao = $request['valor_comissao'];
            $plano_assinatura_afiliado_regiao->dias_trial = $request['dias_trial'];
            $plano_assinatura_afiliado_regiao->quantidade_meses_vigencia = Asaas::getMesesCiclo($request['ciclo']);
            $plano_assinatura_afiliado_regiao->franqueado_regiao_plano_disponibilizado_id = $request['franqueado_regiao_plano_disponibilizado_id'];
            $plano_assinatura_afiliado_regiao->data_pagamento = $request['data_pagamento'];
            $plano_assinatura_afiliado_regiao->data_cancelamento = $request['data_cancelamento'];
            $plano_assinatura_afiliado_regiao->data_expiracao = $request['data_expiracao'];
            $plano_assinatura_afiliado_regiao->statusPlano = $request['statusPlano'];
            $plano_assinatura_afiliado_regiao->ciclo = $request['ciclo'];
            $plano_assinatura_afiliado_regiao->save();
            $data = new ResourcesPlanoAssinaturaAfiliadoRegiao($plano_assinatura_afiliado_regiao);
            return $this->successResponse('Plano assinatura afiliado regiao created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\PlanoAssinaturaAfiliadoRegiao  $planoAssinaturaAfiliadoRegiao
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $plano_assinatura_afiliado_regiao = PlanoAssinaturaAfiliadoRegiao::findOrFail($id);
            if ($plano_assinatura_afiliado_regiao) {
                $plano_assinatura_afiliado_regiao->nome = $request['nome'];
                $plano_assinatura_afiliado_regiao->descricao = $request['descricao'];
                $plano_assinatura_afiliado_regiao->valor = $request['valor'];
                $plano_assinatura_afiliado_regiao->valor_comissao = $request['valor_comissao'];
                $plano_assinatura_afiliado_regiao->dias_trial = $request['dias_trial'];
                $plano_assinatura_afiliado_regiao->quantidade_meses_vigencia = Asaas::getMesesCiclo($request['ciclo']);
                $plano_assinatura_afiliado_regiao->franqueado_regiao_plano_disponibilizado_id = $request['franqueado_regiao_plano_disponibilizado_id'];
                $plano_assinatura_afiliado_regiao->data_pagamento = $request['data_pagamento'];
                $plano_assinatura_afiliado_regiao->data_cancelamento = $request['data_cancelamento'];
                $plano_assinatura_afiliado_regiao->data_expiracao = $request['data_expiracao'];
                $plano_assinatura_afiliado_regiao->statusPlano = $request['statusPlano'];
                $plano_assinatura_afiliado_regiao->ciclo = $request['ciclo'];
                $plano_assinatura_afiliado_regiao->update();
                $data = new ResourcesPlanoAssinaturaAfiliadoRegiao($plano_assinatura_afiliado_regiao);
                return $this->successResponse('Plano assinatura afiliado regiao updated!', $data);
            }
            return $this->errorResponse('Error processing your request');
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function regiao($regiao_id)
    {
        try {
            $planos_assinaturas_afiliado_regiao = PlanoAssinaturaAfiliadoRegiao::join('franqueado_regiao_plano_disponibilizado', 'franqueado_regiao_plano_disponibilizado.id', 'plano_assinatura_afiliado_regiao.franqueado_regiao_plano_disponibilizado_id')
                ->join('franqueado_regiao', 'franqueado_regiao.id', 'franqueado_regiao_plano_disponibilizado.franqueado_regiao_id')
                ->where('franqueado_regiao.regiao_id', $regiao_id)->get();
            return $this->successResponse('Success', $planos_assinaturas_afiliado_regiao);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request!');
        }
    }
}
