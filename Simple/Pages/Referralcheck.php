<?php

if (isset(CMS::$Router->Request->SubUrls[0]) && Users::$Session == false && !isset($_SESSION['referral']) && CMS::$MySql->single('SELECT id FROM users WHERE id = :userid', array('userid' => substr(CMS::$Router->Request->SubUrls[0], 1))))
    $_SESSION['referral'] = substr(CMS::$Router->Request->SubUrls[0], 1);

Site::Stop('/register');
