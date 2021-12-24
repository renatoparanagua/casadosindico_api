<?php


namespace App\Util;

class StatusAssinaturaPlano
{
    public static $ASSINADO = 1;
    public static $AGUARDANDO = 2;
    public static $RESUSADO = 3;
    public static $VISUALIZADO = 4;

    public static function getAllStatus()
    {
        return    [
            self::$ASSINADO,
            self::$AGUARDANDO,
            self::$RESUSADO,
            self::$VISUALIZADO
        ];
    }

    public static function getSelectAllStatus($name, $id, $texto_entrada = "Selecione um status", $selected = null, $onchange = null)
    {
        $mstatus = self::getAllStatus();
        $html = "<select class='form-control' id='$id' name='$name' onchange='$onchange'>";
        if ($texto_entrada) $html .= "<option value=''>$texto_entrada</option>";
        foreach ($mstatus as $status) {
            $texto = self::getLabel($status);
            //$cor = self::getCor($status);
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
            case self::$ASSINADO:
                return "Assinado";
            case self::$AGUARDANDO:
                return "Aguardando";
            case self::$RESUSADO:
                return "Recusado";
            case self::$VISUALIZADO:
                return "Visualizado";
            default:
                return "Indefinido";
        }
    }

    static function getColorTheme($status)
    {
        switch ($status) {
            case self::$ASSINADO:
                return "success";
            case self::$AGUARDANDO:
                return "warning";
            case self::$RESUSADO:
                return "danger";
            case self::$VISUALIZADO:
                return "info";
        }
    }
}
