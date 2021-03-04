<?php

class Request
{	
    public $Url, $SubUrls = Array();

    public function __construct($Url)
    {
        $this->Url = '/' . preg_replace('#/{2,}#', '/', trim($Url, '/'));

        if ($this->Url == '/') {
            $this->Url = '/index';
        }
    }

    public function PopSubUrl()
    {
        $LastPart = strrchr($this->Url, '/');

        $this->Url = substr($this->Url, 0, 0 - strlen($LastPart));
        if (empty($this->Url)) {
            $this->Url = '/error';
            $this->SubUrls = Array();
        } else {
            array_unshift($this->SubUrls, $LastPart);
        }
    }
}