<?php

$this->Map('/Data/DataController', '../Includes/DataController.php');
$this->Map('/error', 'Error.php'); // 404
$this->Map('/index', 'Index.php');
$this->Map('/register', 'Register.php');
$this->Map('/r', 'Referralcheck.php');
$this->Map('/news', 'News.php');
$this->Map('/tag', 'Tag.php');
$this->Map('/staff', 'Staff.php');
$this->Map('/gambleteam', 'Gambleteam.php');
$this->Map('/eventteam', 'Eventteam.php');
$this->Map('/bouwteam', 'Bouwteam.php');
$this->Map('/spamteam', 'Spamteam.php');
$this->Map('/djteam', 'Djteam.php');
$this->Map('/forgot/password', 'Forgot.php');
$this->Map('/home', 'Home.php');
$this->Map('/top', 'Top.php');
$this->Map('/regels', 'Regels.php');
$this->Map('/disconnected', 'Disconnected.php');
$this->Map('/maintenance', 'Maintenance.php');
$this->Map('/foto', 'Foto.php');

if (Users::$Session == true)
{
    if (Users::$Session->HasPermission(CMS::$Config['manage']['dashboard'])) {
        $this->Map('/manage', 'Manage.php');
    }

    $this->Map('/me', 'Me.php');
	$this->Map('/verdienen', 'testen.php');
    $this->Map('/game', 'Game.php');
    $this->Map('/vault', 'Vault.php');
    $this->Map('/community', 'Community.php');
    $this->Map('/changename', 'Changename.php');
    $this->Map('/packages', 'Packages.php');
    $this->Map('/badges', 'Badges.php');
    $this->Map('/profile', 'Profile.php');
    $this->Map('/values', 'Values.php');
    $this->Map('/voucher', 'Voucher.php');
    $this->Map('/applications', 'Applications.php');
    $this->Map('/site', 'Site.php');
}



