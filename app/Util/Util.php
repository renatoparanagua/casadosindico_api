<?php

namespace App\Util;

use App\Models\Configuracao;
use App\Models\Franqueado;
use App\Util\ModusOperandiStatus;

class Util
{

    public static function getModusOperandi()
    {
        $config = Configuracao::orderBy("id", "desc")->first();
        if ($config)
            return $config->modus_operandi;
        else
            return 'debug';
    }

    public static function getTokenAsaasFranqueadoById($franqueado_id)
    {
        $franqueado = Franqueado::where("id", $franqueado_id)->first();
        if ($franqueado) {
            if (($franqueado->token_asaas_producao && self::getModusOperandi() == ModusOperandiStatus::$PRODUCAO) || ($franqueado->token_asaas_debug && self::getModusOperandi() == ModusOperandiStatus::$DEDUB)) {
                $token = self::getModusOperandi() == ModusOperandiStatus::$PRODUCAO ? $franqueado->token_asaas_producao : $franqueado->token_asaas_debug;
                return $token;
            }
        }
        return null;
    }

    public static function getTokenAutentique($franqueado_id)
    {
        $franqueado = Franqueado::where("id", $franqueado_id)->first();
        if ($franqueado) {
            if ($franqueado->token_autentique) {
                return $franqueado->token_autentique;
            }
        }
        return null;
    }
}
