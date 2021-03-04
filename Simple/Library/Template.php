<?php

class Template
{
    public $Title = '';

    public function Output($Page) {
        ob_start();
        require 'Simple/Pages/'.$Page;
        return ob_get_clean();
    }

    private function WriteInc($IncName) {
        require 'Simple/Includes/'.$IncName.'.php';
    }
}