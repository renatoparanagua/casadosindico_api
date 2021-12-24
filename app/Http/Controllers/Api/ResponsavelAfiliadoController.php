<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Controller;

use App\Http\Resources\ResponsavelAfiliado as ResourcesResponsavelAfiliado;
use App\Models\Afiliado;
use App\Models\BO\ResponsavelAfiliadoBO;
use App\Models\CartaoCnpj;
use App\Models\ContratoSocial;
use App\Models\ResponsavelAfiliado;
use Exception;
use Illuminate\Http\Request;

class ResponsavelAfiliadoController extends Controller
{
    public function __construct(Request $request)
    {
        $class_name = new ResponsavelAfiliado();
        parent::__construct($request, $class_name, new Afiliado());
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
            $responsavel_afiliado = new ResponsavelAfiliado();
            $responsavel_afiliado->nome = $request['nome'];
            $responsavel_afiliado->email = $request['email'];
            $responsavel_afiliado->numero_documento = $request['numero_documento'];
            $responsavel_afiliado->CPF = $request['CPF'];
            $responsavel_afiliado->telefone = $request['telefone'];
            $responsavel_afiliado->cargo = $request['cargo'];
            $responsavel_afiliado->afiliado_id = $this->usuario_tipo_id;
            $validacao = ResponsavelAfiliadoBO::validarResponsavelAfiliadoSoft($responsavel_afiliado);
            if ($validacao->verifica()) {
                $responsavel_afiliado->save();
                $data = new ResourcesResponsavelAfiliado($responsavel_afiliado);
                return $this->successResponse('Responsavel afiliado created!', $data);
            } else {
                return $this->errorResponse($validacao->getErros(), 403);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getMessage());
            return $this->errorResponse('Error processing you request');
        }
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Model\ResponsavelAfiliado  $responsavelAfiliado
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        try {
            $responsavel_afiliado = ResponsavelAFiliado::where($this->usuario_logado->tipo . "_id", $this->usuario_tipo_id)->orderByDesc("id")->first();
            $responsavel_afiliado->nome = $this->getValueRequest($request, $responsavel_afiliado, 'nome');
            $responsavel_afiliado->email = $this->getValueRequest($request, $responsavel_afiliado, 'email');
            $responsavel_afiliado->numero_documento = $this->getValueRequest($request, $responsavel_afiliado, 'numero_documento');
            $responsavel_afiliado->CPF = $this->getValueRequest($request, $responsavel_afiliado, 'CPF');
            $responsavel_afiliado->telefone = $this->getValueRequest($request, $responsavel_afiliado, 'telefone');
            $responsavel_afiliado->cargo = $this->getValueRequest($request, $responsavel_afiliado, 'cargo');

            $validacao = ResponsavelAfiliadoBO::validarResponsavelAfiliado($responsavel_afiliado);
            if ($validacao->verifica()) {
                $responsavel_afiliado->update();
                $data = new ResourcesResponsavelAfiliado($responsavel_afiliado);
                return $this->successResponse('Responsavel afiliado atualizado', $data);
            } else {
                return $this->errorResponse($validacao->getErros(), 403);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e->getCode());
        }
    }

    public function documentos()
    {
        $logo = Afiliado::where("id", $this->usuario_tipo_id)->first()->logo;
        $contratoSocial = ContratoSocial::where("afiliado_id", $this->usuario_tipo_id)->orderBy("id", "DESC")->first();
        $cartaoCnpj = CartaoCnpj::where("afiliado_id", $this->usuario_tipo_id)->orderBy("id", "DESC")->first();
        $res = [
            "contrato_social" => $contratoSocial,
            "cartao_cnpj" => $cartaoCnpj,
            "logo" => $logo
        ];
        return $this->successResponse('Success', $res);
    }

    public function partial_update(Request $request)
    {
        try {
            $responsavel_afiliado = ResponsavelAFiliado::where("afiliado_id", $this->usuario_tipo_id)->orderByDesc("id")->first();

		
            $responsavel_afiliado->nome = $this->getValueRequest($request, $responsavel_afiliado, 'nome', true);
            $responsavel_afiliado->email = $this->getValueRequest($request, $responsavel_afiliado, 'email', true);
            $responsavel_afiliado->numero_documento = $this->getValueRequest($request, $responsavel_afiliado, 'numero_documento', true);
            $responsavel_afiliado->CPF = $this->getValueRequest($request, $responsavel_afiliado, 'CPF', true);
            $responsavel_afiliado->telefone = $this->getValueRequest($request, $responsavel_afiliado, 'telefone', true);
            $responsavel_afiliado->cargo = $this->getValueRequest($request, $responsavel_afiliado, 'cargo', true);

            $responsavel_afiliado->cep = $this->getValueRequest($request, $responsavel_afiliado, 'cep', true);
            $responsavel_afiliado->estado = $this->getValueRequest($request, $responsavel_afiliado, 'estado', true);
            $responsavel_afiliado->cidade = $this->getValueRequest($request, $responsavel_afiliado, 'cidade', true);
            $responsavel_afiliado->bairro = $this->getValueRequest($request, $responsavel_afiliado, 'bairro', true);
            $responsavel_afiliado->rua = $this->getValueRequest($request, $responsavel_afiliado, 'rua', true);
            $responsavel_afiliado->numero = $this->getValueRequest($request, $responsavel_afiliado, 'numero', true);
            $responsavel_afiliado->complemento = $this->getValueRequest($request, $responsavel_afiliado, 'complemento', true);


            $validacao = ResponsavelAfiliadoBO::validarResponsavelAfiliado($responsavel_afiliado);
            if ($validacao->verifica()) {
                $responsavel_afiliado->update();

                $data = new ResourcesResponsavelAfiliado($responsavel_afiliado);

                return $this->successResponse('Responsavel afiliado atualizado', $data);
            } else {
                return $this->errorResponse($validacao->getErros(), 403);
            }
        } catch (Exception $e) {
            return $this->errorResponse($e);
        }
    }
}
