<?php


namespace App\Util\Autentique;

use App\Util\ModusOperandiStatus;
use App\Util\Util;
use CURLFile;

class Api
{
    public static function request(
        $token,
        string $query,
        string $contentType,
        $attr = null,
        string $document_id = null
    ) {
        $httpHeader = ["Authorization: Bearer " . $token];

        $postFields = null;

        if (isset($attr['signers']))
            $signers2 = json_encode($attr['signers']);

        $modus_operandi = Util::getModusOperandi();
        if ($modus_operandi == ModusOperandiStatus::$DEDUB) {
            $AUTENTIQUE_DEV_MODE = "true";
        } elseif ($modus_operandi == ModusOperandiStatus::$PRODUCAO) {
            $AUTENTIQUE_DEV_MODE = "false";
        }



        switch ($contentType) {
            case 'json':
                if ($query == "listar") {
                    $postFields = '{"query": "query { documents(showSandbox:' . $AUTENTIQUE_DEV_MODE . ', limit: 60, page: ' . $document_id . ') { total data { id name refusable sortable created_at signatures { public_id name email created_at action { name } link { short_link } user { id name email } viewed { created_at } signed { created_at } rejected { created_at } } files { original signed } } } }"}';
                } elseif ($query == "ler") {
                    $postFields = '{
                        "query": "query { document(id: \"' . $document_id . '\") { id name refusable sortable created_at files { original signed } signatures { public_id name email created_at action { name } link { short_link } user { id name email } email_events { sent opened delivered refused reason } viewed { ...event } signed { ...event } rejected { ...event } } } } fragment event on Event { ipv4 ipv6 reason created_at geolocation { country countryISO state stateISO city zipcode latitude longitude } }",
                        "variables": {}
                    }';
                } elseif ($query == "assinar") {
                    $postFields = '{
                        "query": "mutation { signDocument(id: \"' . $document_id . '\") }",
                        "variables": {}
                    }';
                }
                array_push($httpHeader, 'Content-Type: application/json');
                break;
            case 'form':
                $postFields =  array('operations' => '{"query":"mutation CreateDocumentMutation($document: DocumentInput!, $signers: [SignerInput!]!, $file: Upload!) {createDocument(sandbox: ' . $AUTENTIQUE_DEV_MODE . ', document: $document, signers: $signers, file: $file) {id name refusable sortable created_at signatures { public_id name email created_at action { name } link { short_link } user { id name email }}}}", "variables":{"document": {"name": "' . $attr['document']['name'] . '"},"signers": ' . $signers2 . ',"file":null}}', 'map' => '{"file": ["variables.file"]}', 'file' => new CURLFILE($attr['file']));
                break;
        }

        if (is_null($postFields)) {
            return 'The postfield field cannot be null';
        }

        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => getenv("AUTENTIQUE_URL"),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => $postFields,
            CURLOPT_HTTPHEADER => $httpHeader,
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return json_decode($response);
    }
}
