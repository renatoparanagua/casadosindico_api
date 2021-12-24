<?php

namespace App\Util\Autentique;

class QueryAutentique
{
    /**
     * @var string
     */
    protected $folder;
    protected $file;

    /**
     * Query constructor.
     */
    public function __construct()
    {
        $this->folder = __DIR__ . '/resources/documents/';
    }

    /**
     * @return string|string[]|null
     */
    public function query()
    {
        if (!file_exists("$this->folder$this->file")) {
            return 'File is not found';
        }

        $query = file_get_contents("$this->folder$this->file");
        return $this->format($query);
    }

    /**
     * @param $query
     *
     * @return string|string[]|null
     */
    private function format($query)
    {
        return preg_replace("/[\n\r]/", '', $query);
    }

    /**
     * @param $file
     *
     * @return $this
     */
    public function setFile($file)
    {
        $this->file = $file;

        return $this;
    }
}
