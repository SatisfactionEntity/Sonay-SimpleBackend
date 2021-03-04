<?php

class Router
{
    public $Request;
    private $Maps = Array(), $CurrentMap;

    public function MapPackage()
    {
        require 'Simple/Maps.php';
    }

    private function Map($Url, $Page)
    {
        if (!isset($this->Maps[$Url])) {
            $this->Maps[$Url] = Array(
                'Package' => $this->CurrentMap,
                'Page' => $Page
            );
        }
    }

    public function Load($Url)
    {
        $this->Request = new Request($Url);

        $i = 10;
        while (!isset($this->Maps[$this->Request->Url])) {
            $this->Request->PopSubUrl();
            if ($i-- == 0) exit;
        }

        return $this->Maps[$this->Request->Url];
    }
}