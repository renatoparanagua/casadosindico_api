<?php

namespace App\Http\Controllers\Api;

use App\Models\Configuracao;
use Illuminate\Support\Facades\Http;

class FCM
{
    public static $URL = "https://fcm.googleapis.com/fcm/send";

    private static function getTokenFCM()
    {
        $config = Configuracao::orderBy("id", "desc")->first();
        return $config->api_key_fcm;
    }

    public static function send($token_notification, $titulo, $corpo, $paramns = [])
    {
        $notification = [
            "to" => $token_notification,
            "collapse_key" => "type_a",
            "notification" => [
                "body"                  =>  $corpo,
                "title"                 =>  $titulo,
                "color"                 =>  "#00adef",
                "icon"                  =>  "myicon",
                "sound"                 =>  "default",
                "priority"              =>  "high",
                "show_in_foreground"    =>  true,
                "channel"               =>  "default"
            ],
            "data" => $paramns
        ];

        $response = Http::withHeaders([
            'Authorization' => "key=" . self::getTokenFCM(),
            'Content-Type' => "application/json"
        ])->post(
            self::$URL,
            $notification
        );
        return $response;
    }

    public static function sendToTopic($topico, $titulo, $corpo, $paramns = [])
    {
        $notification = (object) [
            "to" => "/topics/$topico",
            "collapse_key" => "type_a",
            "notification" => [
                "body"                  =>  $corpo,
                "title"                 =>  $titulo,
                "color"                 =>  "#00adef",
                "icon"                  =>  "myicon",
                "sound"                 =>  "default",
                "tag"                   =>  "$topico",
                "priority"              =>  "high",
                "show_in_foreground"    =>  true,
                "channel"               =>  "default"
            ],
            "data" => $paramns
        ];
        $response = Http::withHeaders([
            'Authorization' => "key=" . self::getTokenFCM(),
        ])->post(self::$URL, [
            $notification
        ]);
        return $response;
    }
}
