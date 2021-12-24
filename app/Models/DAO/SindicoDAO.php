<?php

namespace App\Models\DAO;

use App\Models\Sindico;
use Exception;

class SindicoDAO
{
    public static function cadastrar($requestData)
    {
        try {
            $sindico = Sindico::create($requestData);
            return Sindico::where("id", $sindico->id)->first();
        } catch (Exception $e) {
            return null;
        }
    }
}
