<?php

define("MAINTENANCE", false);

require 'CMS.php';

session_start();

CMS::Init();

CMS::$Cache = new Memcached();
CMS::$Cache->addServer(CMS::$Config['cms']['cachehost'], CMS::$Config['cms']['cacheport']);

CMS::$MySql = new Db(CMS::$Config['mysql']);
CMS::$Router = new Router();

if (isset($_COOKIE['simple_user_hash'])) {
    Users::CheckLogin($_COOKIE['simple_user_hash']);
}

CMS::$Router->MapPackage();



$uriSegments = explode("/", parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

if(MAINTENANCE == TRUE){
	/* Heb je een redirect functie? */
	if($uriSegments[1] == "maintenance"){
	}else{
	header('Location: /maintenance');
	}
}
