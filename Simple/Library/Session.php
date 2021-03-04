<?php

class Session
{	
    public function __construct($Row)
    {
        $this->ID = $Row['id'];
        $this->Data = $Row;
    }

    public function HasPermission($Permission)
    {
        return strpos($Permission, '|'.$this->Data['rank'].'|') !== false;
    }
}