<?php

namespace App\Models\BO;

use App\Models\AfiliadoOrcamentoInteresse;
use App\Models\Categoria;
use App\Models\ImagemOrcamento;
use App\Models\Orcamento;
use App\Models\OrcamentoAssinatura;
use App\Models\Sindico;
use App\Models\Vistoria;
use App\Models\VistoriaImagem;
use App\Util\Formatacao;
use App\Util\StatusOrcamento;
use App\Util\StatusVistoria;
use App\Util\Validacao;
use Carbon\Carbon;

class OrcamentoBO
{

    public static function validarOrcamento($orcamento)
    {
        $validacao = new Validacao();
        $validacao->obrigatorio("categoria_id", $orcamento->categoria_id, "Categoria");
        $validacao->obrigatorio("condominio_id", $orcamento->condominio_id, "Condominio");
        $validacao->obrigatorio("nome", $orcamento->nome, "Título");
        $validacao->obrigatorio("descricao", $orcamento->descricao, "Descrição");

        $validacao->inteiro("categoria_id", $orcamento->categoria_id, "Categoria");
        $validacao->inteiro("condominio_id", $orcamento->condominio_id, "Condominio");

        if ($orcamento->categoria_id <= 0) {
            $validacao->mensagem[] = array("error_code" => "invalid-categoria", "error_message" => "Categoria é obrigatório.");
        }
        if ($orcamento->condominio_id <= 0) {
            $validacao->mensagem[] = array("error_code" => "invalid-condominio", "error_message" => "Condomínio é obrigatório.");
        }

        return $validacao;
    }

    public static function transform($orcamento, $tipo_usuario, $usuario_tipo_id)
    {
        if ($orcamento) {
            $orcaAux = Orcamento::where("id", $orcamento->id)->first();
            $orcaAux->data_atualizacao = Carbon::now();
            $orcaAux->update();
        }

        $condominio = $orcamento->condominio()->withTrashed()->first();
        $orcamento['condominio'] = $condominio;
        $orcamento['sindico'] = Sindico::withTrashed()->where("id", $condominio->sindico_id)->first();
        $orcamento['status_label'] = StatusOrcamento::getLabel($orcamento->status);
        $orcamento['status_cor'] = StatusOrcamento::getCor($orcamento->status);

        $orcamento['afiliado'] = $orcamento->afiliado()->withTrashed()->first();
        if ($orcamento['afiliado']) {
            $orcamento['afiliado']["razao_social"] = $orcamento['afiliado']["nome_fantasia"] ? $orcamento['afiliado']["nome_fantasia"] : $orcamento['afiliado']["razao_social"];
        }
        $orcamento['categoria'] = Categoria::withTrashed()->where("id", $orcamento->categoria_id)->first();
        $orcamento['status_sindico_label'] = StatusOrcamento::getLabel($orcamento->status_sindico);
        $orcamento['status_sindico_cor'] = StatusOrcamento::getCor($orcamento->status_sindico);

        $orcamento['status_afiliado_label'] = StatusOrcamento::getLabel($orcamento->status_afiliado);
        $orcamento['status_afiliado_cor'] = StatusOrcamento::getCor($orcamento->status_afiliado);
        $orcamento['data_cadastro_show'] = Formatacao::data($orcamento['data_cadastro']);
        $orcamento['data_atualizacao_show'] = Formatacao::data($orcamento['data_atualizacao']);
        $orcamento['data_inicio_operacao_show'] = $orcamento['data_inicio_operacao'] ? Formatacao::data($orcamento['data_inicio_operacao'], false, false) : null;
        $orcamento['data_fim_operacao_show'] = $orcamento['data_fim_operacao'] ? Formatacao::data($orcamento['data_fim_operacao'], false, false) : null;
        $orcamento['vistorias'] = Vistoria::where("orcamento_id", $orcamento['id'])->orderBy("id", "desc")->get();
        $orcamento['imagens'] = ImagemOrcamento::where("orcamento_id", $orcamento['id'])->get();
        $orcamento['assinatura'] = OrcamentoAssinatura::where($tipo_usuario . "_id", $usuario_tipo_id)->where("orcamento_id", $orcamento['id'])->orderBy("id", "desc")->first();
        if ($orcamento['assinatura'] && $orcamento['assinatura']['signed'] != "") {
            $orcamento['assinatura']['signed'] = Formatacao::data($orcamento['assinatura']['signed']);
        }

        if ($tipo_usuario == "afiliado") {
            $orcamento['interesse'] = AfiliadoOrcamentoInteresse::where("orcamento_id", $orcamento->id)->where("afiliado_id", $usuario_tipo_id)->orderBy("id", "desc")->first();
        } elseif ($tipo_usuario == "sindico") {
            $orcamento['interesse'] = AfiliadoOrcamentoInteresse::where("orcamento_id", $orcamento->id)->where("descartado_afiliado", 0)->whereIn("descartado_sindico", [-1, 0])->get();
        }

        foreach ($orcamento['vistorias'] as $index => $v) {
            $orcamento['vistorias'][$index]['data_cadastro_show'] = Formatacao::data($v['data_cadastro']);
            $orcamento['vistorias'][$index]['data_vistoria_show'] = Formatacao::data($v['data_vistoria'], false, false) . ($v['hora_vistoria'] ? " - " . Formatacao::hora($v['hora_vistoria'], true, true, false) : "");
            $orcamento['vistorias'][$index]['data_checkin_show'] = Formatacao::data($v['data_checkin']);
            $orcamento['vistorias'][$index]['data_checkout_show'] = Formatacao::data($v['data_checkout']);
            $orcamento['vistorias'][$index]['vistoriador'] = $v->vistoriador()->withTrashed()->first();
            $orcamento['vistorias'][$index]['status_label'] = StatusVistoria::getLabel($v->status);
            $orcamento['vistorias'][$index]['status_color'] = StatusVistoria::getColor($v->status);
            $orcamento['vistorias'][$index]['fotos'] = VistoriaImagem::where("vistoria_id", $v->id)->orderBy("id", "asc")->get();
        }
        return $orcamento;
    }

    public static function transformList($orcamento, $tipo_usuario, $usuario_tipo_id)
    {

        if ($orcamento) {
            $orcaAux = Orcamento::where("id", $orcamento->id)->first();
            $orcaAux->data_atualizacao = Carbon::now();
            $orcaAux->update();
        }
        $condominio = $orcamento->condominio()->withTrashed()->first();
        $orcamento['condominio'] = $condominio;
        $orcamento['sindico'] = Sindico::withTrashed()->where("id", $condominio->sindico_id)->first();
        $orcamento['status_label'] = StatusOrcamento::getLabel($orcamento->status);
        $orcamento['status_cor'] = StatusOrcamento::getCor($orcamento->status);

        $orcamento['afiliado'] = $orcamento->afiliado()->withTrashed()->first();
        if ($orcamento['afiliado']) {
            $orcamento['afiliado']["razao_social"] = $orcamento['afiliado']["nome_fantasia"] ? $orcamento['afiliado']["nome_fantasia"] : $orcamento['afiliado']["razao_social"];
        }
        $orcamento['categoria'] = Categoria::withTrashed()->where("id", $orcamento->categoria_id)->first();
        $orcamento['status_sindico_label'] = StatusOrcamento::getLabel($orcamento->status_sindico);
        $orcamento['status_sindico_cor'] = StatusOrcamento::getCor($orcamento->status_sindico);

        $orcamento['status_afiliado_label'] = StatusOrcamento::getLabel($orcamento->status_afiliado);
        $orcamento['status_afiliado_cor'] = StatusOrcamento::getCor($orcamento->status_afiliado);
        $orcamento['data_cadastro_show'] = Formatacao::data($orcamento['data_cadastro']);
        $orcamento['data_atualizacao_show'] = Formatacao::data($orcamento['data_atualizacao']);
        $orcamento['data_inicio_operacao_show'] = $orcamento['data_inicio_operacao'] ? Formatacao::data($orcamento['data_inicio_operacao'], false, false) : null;
        $orcamento['data_fim_operacao_show'] = $orcamento['data_fim_operacao'] ? Formatacao::data($orcamento['data_fim_operacao'], false, false) : null;
        $orcamento['vistorias'] = Vistoria::where("orcamento_id", $orcamento['id'])->orderBy("id", "desc")->get();
        $orcamento['imagens'] = ImagemOrcamento::where("orcamento_id", $orcamento['id'])->get();
        $orcamento['assinatura'] = OrcamentoAssinatura::where($tipo_usuario . "_id", $usuario_tipo_id)->where("orcamento_id", $orcamento['id'])->orderBy("id", "desc")->first();
        if ($orcamento['assinatura'] && $orcamento['assinatura']['signed'] != "") {
            $orcamento['assinatura']['signed'] = Formatacao::data($orcamento['assinatura']['signed']);
        }

        if ($tipo_usuario == "afiliado") {
            $orcamento['interesse'] = AfiliadoOrcamentoInteresse::where("orcamento_id", $orcamento->id)->where("afiliado_id", $usuario_tipo_id)->orderBy("id", "desc")->first();
        } elseif ($tipo_usuario == "sindico") {
            $orcamento['interesse'] = AfiliadoOrcamentoInteresse::where("orcamento_id", $orcamento->id)->where("descartado_afiliado", 0)->whereIn("descartado_sindico", [-1, 0])->get();
        }

        foreach ($orcamento['vistorias'] as $index => $v) {
            $orcamento['vistorias'][$index]['data_cadastro_show'] = Formatacao::data($v['data_cadastro']);
            $orcamento['vistorias'][$index]['data_vistoria_show'] = Formatacao::data($v['data_vistoria'], false, false) . ($v['hora_vistoria'] ? " - " . Formatacao::hora($v['hora_vistoria'], true, true, false) : "");
            $orcamento['vistorias'][$index]['data_checkin_show'] = Formatacao::data($v['data_checkin']);
            $orcamento['vistorias'][$index]['data_checkout_show'] = Formatacao::data($v['data_checkout']);
            $orcamento['vistorias'][$index]['vistoriador'] = $v->vistoriador()->withTrashed()->first();
            $orcamento['vistorias'][$index]['status_label'] = StatusVistoria::getLabel($v->status);
            $orcamento['vistorias'][$index]['status_color'] = StatusVistoria::getColor($v->status);
            $orcamento['vistorias'][$index]['fotos'] = VistoriaImagem::where("vistoria_id", $v->id)->orderBy("id", "asc")->get();
        }
        return $orcamento;
    }
}
