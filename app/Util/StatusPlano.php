<?php


namespace App\Util;

class StatusPlano
{
    public static $ATIVO = 1;
    public static $CANCELADO = 2;
    public static $EM_PROCESSO_CANCELAMENTO = 3;
    public static $INADIMPLENTE = 4;
    public static $PENDENTE = 5;

    public static function getAllStatus()
    {
        return    [
            self::$ATIVO,
            self::$CANCELADO,
            self::$EM_PROCESSO_CANCELAMENTO,
            self::$INADIMPLENTE,
            self::$PENDENTE
        ];
    }

    public static function getSelectAllStatus($name, $id, $texto_entrada = "Selecione um status", $selected = null, $onchange = null)
    {
        $mstatus = self::getAllStatus();
        $html = "<select class='form-control' id='$id' name='$name' onchange='$onchange'>";
        if ($texto_entrada) $html .= "<option value=''>$texto_entrada</option>";
        foreach ($mstatus as $status) {
            $texto = self::getLabel($status);
            $cor = self::getCor($status);
            if ($selected == $status)
                $html .= "<option value='$status' selected >" . $texto . "</option>";
            else
                $html .= "<option value='$status' >" . $texto . "</option>";
        }
        $html .= "</select>";
        return $html;
    }

    static function getLabel($status)
    {
        switch ($status) {
            case self::$ATIVO:
                return "Ativo";
            case self::$CANCELADO:
                return "Inativo";
            case self::$EM_PROCESSO_CANCELAMENTO:
                return "Em processo de cancelamento";
            case self::$INADIMPLENTE:
                return "Inadimplente";
            case self::$PENDENTE:
                return "Pendente";
            default:
                return "Indefinido";
        }
    }

    static function getCor($status)
    {
        switch ($status) {
            case self::$ATIVO:
                return "#ffff00";
            case self::$INADIMPLENTE:
                return "#795548";
            case self::$EM_PROCESSO_CANCELAMENTO:
                return "#1976d2";
            case self::$CANCELADO:
                return "#d32f2f";
            case self::$PENDENTE:
                return "#d32f2f";
            case self::$EM_PROCESSO_CANCELAMENTO:
                return "#d32f2f";
        }
    }

    static function getColorTheme($status)
    {
        switch ($status) {
            case self::$ATIVO:
                return "success";
            case self::$INADIMPLENTE:
                return "danger";
            case self::$EM_PROCESSO_CANCELAMENTO:
                return "warning";
            case self::$CANCELADO:
                return "info";
            case self::$PENDENTE:
                return "warning";
            case self::$EM_PROCESSO_CANCELAMENTO:
                return "warning";
        }
    }
}
