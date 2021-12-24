<?php

namespace App\Util;

class StatusVistoria
{
    public static $PENDENTE = "pendente";
    public static $EM_ANDAMENTO = "em_andamento";
    public static $CONCLUIDO = "concluido";
    public static $CANCELADO = "cancelado";

    static function getLabel($status)
    {
        switch ($status) {
            case self::$PENDENTE:
                return "Em aberto";
            case self::$EM_ANDAMENTO:
                return "Em andamento (Necessário check-out)";
            case self::$CONCLUIDO:
                return "Concluido";
            case self::$CANCELADO:
                return "Cancelado";
        }
    }

    static function getColor($status)
    {
        switch ($status) {
            case self::$PENDENTE:
                return "warning";
            case self::$EM_ANDAMENTO:
                return "danger";
            case self::$CONCLUIDO:
                return "success";
            case self::$CANCELADO:
                return "danger";
        }
    }
}
