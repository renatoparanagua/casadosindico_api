<?php

namespace App\Util;


class StatusOrcamento
{

    public static $ANALISANDO_CANDIDATOS = 1;
    public static $ANALISANDO_ORCAMENTOS = 2;
    public static $AGUARDANDO_CONTRATO = 3;
    public static $EM_EXECUCAO = 4;
    public static $FINALIZADO = 5;
    public static $CANCELADO_PELO_ADMIN = 6;
    public static $CANCELADO_PELO_FRANQUEADO = 7;
    public static $CANCELADO_PELO_SINDICO = 8;
    public static $CANCELADO_PELO_AFILIADO = 9;
    public static $CONTRATO_ASSINADO = 10;

    static function getLabel($status)
    {
        switch ($status) {
            case self::$ANALISANDO_CANDIDATOS:
                return "Analisando candidatos";
            case self::$ANALISANDO_ORCAMENTOS:
                return "Em cotação";
            case self::$AGUARDANDO_CONTRATO:
                return "Aguardando contrato";
            case self::$CONTRATO_ASSINADO:
                return "Contrato assinado";
            case self::$EM_EXECUCAO:
                return "Em execução";
            case self::$FINALIZADO:
                return "Concluido";
            case self::$CANCELADO_PELO_ADMIN:
                return "Cancelado pelo administrador";
            case self::$CANCELADO_PELO_FRANQUEADO:
                return "Cancelado pelo franqueado";
            case self::$CANCELADO_PELO_SINDICO:
                return "Cancelado pelo síndico";
            case self::$CANCELADO_PELO_AFILIADO:
                return "Cancelado pelo afiliado";
        }
    }

    static function getLabelAfiliado($status)
    {
        switch ($status) {
            case self::$ANALISANDO_CANDIDATOS:
                return "Aberto";
            case self::$ANALISANDO_ORCAMENTOS:
                return "Em cotação";
            case self::$AGUARDANDO_CONTRATO:
                return "Aguardando contrato";
            case self::$CONTRATO_ASSINADO:
                return "Contrato assinado";
            case self::$EM_EXECUCAO:
                return "Em execução";
            case self::$FINALIZADO:
                return "Concluido";
            case self::$CANCELADO_PELO_ADMIN:
                return "Cancelado pelo administrador";
            case self::$CANCELADO_PELO_FRANQUEADO:
                return "Cancelado pelo franqueado";
            case self::$CANCELADO_PELO_SINDICO:
                return "Cancelado pelo síndico";
            case self::$CANCELADO_PELO_AFILIADO:
                return "Cancelado pelo afiliado";
        }
    }

    static function getCor($status)
    {
        switch ($status) {
            case self::$ANALISANDO_CANDIDATOS:
                return "#ffff00";
            case self::$ANALISANDO_ORCAMENTOS:
                return "#795548";
            case self::$AGUARDANDO_CONTRATO:
                return "#1976d2";
            case self::$CONTRATO_ASSINADO:
                return "#00c853";
            case self::$EM_EXECUCAO:
                return "#00c853";
            case self::$FINALIZADO:
                return "#6200ea";
            case self::$CANCELADO_PELO_ADMIN:
                return "#d32f2f";
            case self::$CANCELADO_PELO_FRANQUEADO:
                return "#d32f2f";
            case self::$CANCELADO_PELO_SINDICO:
                return "#d32f2f";
            case self::$CANCELADO_PELO_AFILIADO:
                return "#d32f2f";
        }
    }
}
