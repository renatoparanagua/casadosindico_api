<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;
use App\Http\Resources\Vistoria as ResourcesVistoria;
use App\Models\Condominio;
use App\Models\Franqueado;
use App\Models\FranqueadoRegiao;
use App\Models\Orcamento;
use App\Models\Sindico;
use App\Models\UsuarioApp;
use App\Models\Vistoria;
use App\Models\Vistoriador;
use App\Models\VistoriaImagem;
use App\Models\VistoriaRejeitadaVistoriador;
use App\Util\Formatacao;
use App\Util\StatusVistoria;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Date;

class VistoriaController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request, new Vistoria(), new Vistoriador());
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->newLog("Listando vistorias");
        try {

            $vistoriador = Vistoriador::where("id", $this->usuario_tipo_id)->first();
            $vistoriasRes = Vistoria::with("vistoriador")->with("orcamento.categoria")->withTrashed("orcamento")->with("orcamento.condominio.sindico")->with("orcamento.condominio")->where("vistoriador_id", null, null, "or")->where("vistoriador_id", "=", $this->usuario_tipo_id, "or")->orderBy("id", "asc")->get();

            $vistorias = [];

            foreach ($vistoriasRes as $key => $vistoria) {

                if ($vistoria['orcamento'] != null) {
                    $regiao_vistoria = $vistoria['orcamento']['regiao_id'];
                    $regioesVistoriador = FranqueadoRegiao::where("franqueado_id", $vistoriador->franqueado_id)->where("regiao_id", $regiao_vistoria)->first();

                    $vistoriasRejeitadas = VistoriaRejeitadaVistoriador::where("vistoria_id", $vistoria->id)->where("vistoriador_id", $this->usuario_tipo_id)->first();

                    if ($vistoriasRejeitadas == null) {
                        if ($vistoriador->franqueado_id == null) {
                            $vistoriasRes[$key]['data_cadastro'] = Formatacao::data($vistoria['data_cadastro']);
                            if ($vistoriasRes[$key]['data_aceite'])
                                $vistoriasRes[$key]['data_aceite_show'] = Formatacao::data($vistoriasRes[$key]['data_aceite']);
                            $vistoriasRes[$key]["imagens"] = VistoriaImagem::where("vistoria_id", $vistoria->id)->get();


                            $vistoriasRes[$key]['data_cadastro_show'] = Formatacao::data($vistoriasRes[$key]['data_cadastro']);
                            $vistoriasRes[$key]['data_vistoria_show'] = $vistoriasRes[$key]['data_vistoria'] ? Formatacao::data($vistoriasRes[$key]['data_vistoria'], false, false) : null;
                            $vistoriasRes[$key]['data_checkin_show'] = Formatacao::data($vistoriasRes[$key]['data_checkin']);
                            $vistoriasRes[$key]['data_checkout_show'] = Formatacao::data($vistoriasRes[$key]['data_checkout']);
                            $vistoriasRes[$key]['data_aceite_show'] = Formatacao::data($vistoriasRes[$key]['data_aceite']);
                            $vistoriasRes[$key]['status_label'] = StatusVistoria::getLabel($vistoriasRes[$key]->status);
                            $vistoriasRes[$key]['status_color'] = StatusVistoria::getColor($vistoriasRes[$key]->status);

                            $vistorias[] = $vistoriasRes[$key];
                        } else if ($regioesVistoriador) {
                            $vistoriasRes[$key]['data_cadastro'] = Formatacao::data($vistoria['data_cadastro']);
                            $vistoriasRes[$key]["imagens"] = VistoriaImagem::where("vistoria_id", $vistoria->id)->get();
                            if ($vistoriasRes[$key]['data_aceite'])
                                $vistoriasRes[$key]['data_aceite_show'] = Formatacao::data($vistoriasRes[$key]['data_aceite']);

                            $vistoriasRes[$key]['data_cadastro_show'] = Formatacao::data($vistoriasRes[$key]['data_cadastro']);
                            $vistoriasRes[$key]['data_vistoria_show'] = $vistoriasRes[$key]['data_vistoria'] ? Formatacao::data($vistoriasRes[$key]['data_vistoria'], false, false) : null;
                            $vistoriasRes[$key]['data_checkin_show'] = Formatacao::data($vistoriasRes[$key]['data_checkin']);
                            $vistoriasRes[$key]['data_checkout_show'] = Formatacao::data($vistoriasRes[$key]['data_checkout']);
                            $vistoriasRes[$key]['data_aceite_show'] = Formatacao::data($vistoriasRes[$key]['data_aceite']);
                            $vistoriasRes[$key]['status_label'] = StatusVistoria::getLabel($vistoriasRes[$key]->status);
                            $vistoriasRes[$key]['status_color'] = StatusVistoria::getColor($vistoriasRes[$key]->status);


                            $vistorias[] = $vistoriasRes[$key];
                        }
                    }
                }
            }

            return $this->successResponse('Success', $vistorias);
        } catch (Exception $e) {
            return $this->errorResponse($e);
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
        $this->newLog("Nova vistoria");
        try {
            $vistoria = new Vistoria();
            $vistoria->descricao = $request['descricao'];
            $vistoria->vistoriador_id = $this->usuario_tipo_id;
            $vistoria->orcamento_id = $request['orcamento_id'];
            $vistoria->save();
            $data = new ResourcesVistoria($vistoria);

            $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();
            $franqueadoRegiao = FranqueadoRegiao::where("regiao_id", $orcamento->regiao_id)->where("status", "ativo")->orderBy("id", "desc")->first();
            if ($franqueadoRegiao) {
                $vistoriadores = Vistoriador::where("franqueado_id", "=", $franqueadoRegiao->franqueado_id, "or")->where("franqueado_id", "=", null, "or")->get();
                foreach ($vistoriadores as $vistoriador) {
                    $usuarioApp = UsuarioApp::where("id", $vistoriador->usuario_app_id)->first();
                    if (isset($usuarioApp->token_notification)) {
                        SenderNotificacao::novaVistoria($usuarioApp->token_notification, $vistoriador);
                    }
                }
            } else {
                $vistoriadores = Vistoriador::where("franqueado_id", "=", null)->get();
                foreach ($vistoriadores as $vistoriador) {
                    $usuarioApp = UsuarioApp::where("id", $vistoriador->usuario_app_id)->first();
                    if (isset($usuarioApp->token_notification)) {
                        SenderNotificacao::novaVistoria($usuarioApp->token_notification, $vistoriador);
                    }
                }
            }


            $orcamento->data_atualizacao = Carbon::now();
            $orcamento->update();
            return $this->successResponse('Vistoria created!', $data);
        } catch (Exception $e) {
            return $this->errorResponse($e);
            return $this->errorResponse('Error processing your request');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\Vistoria  $vistoria
     * @return \Illuminate\Http\Response
     */
    public function partial_update(Request $request, $id)
    {
        $this->newLog("Atualizando vistoria");
        try {
            $vistoria = Vistoria::where('vistoriador_id', $this->usuario_tipo_id)->findOrFail($id);
            $vistoria->descricao = $this->getValueRequest($request, $vistoria, 'descricao', true);
            $vistoria->data_vistoria = $this->getValueRequest($request, $vistoria, 'data_vistoria', true);
            $vistoria->data_checkout = $this->getValueRequest($request, $vistoria, 'data_checkout', true);
            $vistoria->latitude_checkout = $this->getValueRequest($request, $vistoria, 'latitude_checkout', true);
            $vistoria->longitude_checkout = $this->getValueRequest($request, $vistoria, 'longitude_checkout', true);
            $vistoria->data_checkin = $this->getValueRequest($request, $vistoria, 'data_checkin', true);
            $vistoria->latitude_checkin = $this->getValueRequest($request, $vistoria, 'latitude_checkin', true);
            $vistoria->longitude_checkin = $this->getValueRequest($request, $vistoria, 'longitude_checkin', true);
            $vistoria->update();

            $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();
            $orcamento->data_atualizacao = Carbon::now();
            $orcamento->update();

            $data = new ResourcesVistoria($vistoria);
            return $this->successResponse('Vistoria updated!', $data);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function update(Request $request, $id)
    {
        $this->newLog("Atualizando vistoria");
        try {
            $vistoria = Vistoria::findOrFail($id);
            $vistoria->descricao_vistoriador = $request['descricao_vistoriador'];
            $vistoria->update();

            $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();
            $orcamento->data_atualizacao = Carbon::now();
            $orcamento->update();

            return $this->successResponse('Vistoria atualizada!', $vistoria);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function checkin($id, Request $request)
    {
        $this->newLog("CHECKIN vistoria $id");
        try {
            $vistoria = Vistoria::findOrFail($id);
            $vistoria->vistoriador_id = $this->usuario_tipo_id;

            if ($request['data_checkin'] != null)
                $vistoria->data_checkin = Formatacao::data($request['data_checkin'], true);
            else
                $vistoria->data_checkin = Carbon::now();

            $vistoria->latitude_checkin = $request['latitude_checkin'];
            $vistoria->longitude_checkin = $request['longitude_checkin'];

            $vistoria->status = StatusVistoria::$EM_ANDAMENTO;
            $vistoria->update();

            $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();
            if ($orcamento) {
                $condominio = Condominio::withTrashed()->where("id", $orcamento->condominio_id)->first();
                if ($condominio) {
                    $sindico = Sindico::where("id", $condominio->sindico_id)->first();
                    if ($sindico) {
                        $usuarioApp = UsuarioApp::where("id", $sindico->usuario_app_id)->first();
                        if ($usuarioApp) {
                            if ($usuarioApp->token_notification) {
                                $vistoriador = Vistoriador::where("id", $vistoria->vistoriador_id)->first();
                                SenderNotificacao::vistoriadorCheckin($usuarioApp->token_notification, $sindico, $vistoria, $vistoriador);
                            }
                        }
                    }
                }
            }

            $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();
            $orcamento->data_atualizacao = Carbon::now();
            $orcamento->update();

            return $this->successResponse('Checkin realizado', $vistoria);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function checkout($id, Request $request)
    {
        $this->newLog("CHECKOUT vistoria $id");
        try {
            $vistoria = Vistoria::findOrFail($id);
            $vistoria->vistoriador_id = $this->usuario_tipo_id;

            if ($request['data_checkout'] != null)
                $vistoria->data_checkout = Formatacao::data($request['data_checkout'], true);
            else
                $vistoria->data_checkout = Carbon::now();

            $vistoria->latitude_checkout = $request['latitude_checkout'];
            $vistoria->longitude_checkout = $request['longitude_checkout'];

            $vistoria->status = StatusVistoria::$CONCLUIDO;
            $vistoria->update();

            $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();
            if ($orcamento) {
                $condominio = Condominio::withTrashed()->where("id", $orcamento->condominio_id)->first();
                if ($condominio) {
                    $sindico = Sindico::where("id", $condominio->sindico_id)->first();
                    if ($sindico) {
                        $usuarioApp = UsuarioApp::where("id", $sindico->usuario_app_id)->first();
                        if ($usuarioApp) {
                            if ($usuarioApp->token_notification) {
                                $vistoriador = Vistoriador::where("id", $vistoria->vistoriador_id)->first();
                                SenderNotificacao::vistoriadorCheckout($usuarioApp->token_notification, $sindico, $vistoria, $vistoriador);
                            }
                        }
                    }
                }
            }

            $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();
            $orcamento->data_atualizacao = Carbon::now();
            $orcamento->update();

            return $this->successResponse('Checkout realizado', $vistoria);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function condominio($condominio_id)
    {
        try {
            $vistorias = $this->class_name::join('orcamento', 'orcamento.id', 'vistoria.orcamento_id')
                ->where('orcamento.condominio_id', $condominio_id)
                ->where('vistoria.vistoriador_id', $this->usuario_tipo_id)
                ->select('vistoria.*')
                ->get();
            return $this->successResponse('Success', $vistorias);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function data($data)
    {
        try {
            $vistorias = $this->class_name::where('vistoria.vistoriador_id', $this->usuario_tipo_id)
                ->whereDate('data_vistoria', $data)
                ->get();
            return $this->successResponse('Success', $vistorias);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }

    public function show($id = null)
    {
        $this->newLog("Visualizando vistoria $id");
        try {
            $vistoria = Vistoria::findOrFail($id);
            $vistoria['data_cadastro_show'] = Formatacao::data($vistoria['data_cadastro']);
            $vistoria['data_vistoria_show'] = $vistoria['data_vistoria'] ? Formatacao::data($vistoria['data_vistoria'], false, false) : null;
            $vistoria['data_checkin_show'] = Formatacao::data($vistoria['data_checkin']);
            $vistoria['data_checkout_show'] = Formatacao::data($vistoria['data_checkout']);
            $vistoria['data_aceite_show'] = Formatacao::data($vistoria['data_aceite']);
            $vistoria['vistoriador'] = $vistoria->vistoriador;
            $vistoria['status_label'] = StatusVistoria::getLabel($vistoria->status);
            $vistoria['status_color'] = StatusVistoria::getColor($vistoria->status);
            $vistoria['fotos'] = VistoriaImagem::where("vistoria_id", $id)->orderBy("id", "asc")->get();
            return $this->successResponse('Success', $vistoria);
        } catch (Exception $e) {
            return $this->errorResponse('Error processing your request');
        }
    }



    public function rejeitar($vistoria_id)
    {
        $res = VistoriaRejeitadaVistoriador::create([
            "vistoria_id" => $vistoria_id,
            "vistoriador_id" => $this->usuario_tipo_id,
        ]);

        return $this->successResponse('Success', $res);
    }

    public function aceitar($vistoria_id)
    {
        $vistoria = Vistoria::where("id", $vistoria_id)->first();

        if ($vistoria->vistoriador_id > 0) {
            $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();
            $orcamento->data_atualizacao = Carbon::now();
            $orcamento->update();
            return $this->errorResponse(
                [
                    [
                        "error_code" => "invalid-request",
                        "error_message" => 'O vistoriador ' . $vistoria->vistoriador->nome . ' já está encarregado desta vistoria.'
                    ]
                ],
                403
            );
        } else {
            $vistoria->vistoriador_id = $this->usuario_tipo_id;
            $vistoria->data_aceite = Carbon::now();
            $vistoria->update();

            $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();
            $orcamento->data_atualizacao = Carbon::now();
            $orcamento->update();




            return $this->successResponse('Success', $vistoria);
        }
    }

    public function agendar($vistoria_id, Request $request)
    {
        $vistoria = Vistoria::where("id", $vistoria_id)->first();
        if ($request['data_vistoria_show'])
            $vistoria->data_vistoria = Formatacao::data($request['data_vistoria_show']);

        if ($request['hora_vistoria'] != null)
            $vistoria->hora_vistoria = $request['hora_vistoria'];

        $vistoria->update();

        //Enviar notificação para o sindico
        $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();
        if ($orcamento) {
            $condominio = Condominio::withTrashed()->where("id", $orcamento->condominio_id)->first();
            if ($condominio) {
                $sindico = Sindico::where("id", $condominio->sindico_id)->first();
                if ($sindico) {
                    $usuarioApp = UsuarioApp::where("id", $sindico->usuario_app_id)->first();
                    if ($usuarioApp) {
                        if ($usuarioApp->token_notification) {
                            $vistoriador = Vistoriador::where("id", $vistoria->vistoriador_id)->first();
                            SenderNotificacao::vistoriaAgendada($usuarioApp->token_notification, $sindico, $vistoria, $vistoriador);
                        }
                    }
                }
            }
        }

        $orcamento = Orcamento::where("id", $vistoria->orcamento_id)->first();
        $orcamento->data_atualizacao = Carbon::now();
        $orcamento->update();

        return $this->successResponse('Success', $vistoria);
    }
}
