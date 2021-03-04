<?php

class CMS
{
    public static $Config = Array();
    public static $Lang = Array();
    public static $Cache, $MySql, $Router, $Template;

    public static function Init()
    {
        $IP = $_SERVER['REMOTE_ADDR'];

	$Cloudflare_ips = Array();
	
	$IP = htmlspecialchars(isset($_SERVER['HTTP_CF_CONNECTING_IP']) ? $_SERVER['HTTP_CF_CONNECTING_IP'] : $_SERVER['REMOTE_ADDR']);

        define('RemoteIp', $IP);

        foreach (glob('Simple/Library/*.php') as $Class) {
            require $Class;
        }

        require 'Simple/System/Configuration/Config.php';
        require 'Simple/System/Languages/'.CMS::$Config['cms']['language'].'.php';

    }
}
