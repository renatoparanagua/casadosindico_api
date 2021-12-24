<?php


namespace App\Util\Autentique;

class DocumentosAutentique
{

    /**
     * List all documents
     *
     * @param  int  $page
     * @return bool|string
     */
    public static function listAll($token, int $page = 1)
    {
        return Api::request($token, "listar", 'json', null, $page);
    }

    /**
     * List document by id
     *
     * @param string $documentId
     *
     * @return bool|string
     */
    public static function listById($token, string $documentId)
    {
        return Api::request($token, "ler", 'json', null, $documentId);
    }

    /**
     * Create Document
     *
     * @param array $attributes
     * @return bool|false|string
     */
    public static function create($token, array $attributes)
    {
        return Api::request(
            $token,
            "cadastrar",
            'form',
            $attributes
        );
    }

    /**
     * Sign document by id
     *
     * @param string $documentId
     *
     * @return bool|string
     */
    public static function signById($token, string $documentId)
    {
        return Api::request($token, "assinar", 'json', null, $documentId);
    }

    /**
     * Delete document by id
     *
     * @param string $documentId
     *
     * @return bool|string
     */
    public static function deleteById($token, string $documentId)
    {
        return Api::request($token, "deletar", 'json', null, $documentId);
    }
}
