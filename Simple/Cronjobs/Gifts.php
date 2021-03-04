<?php

chdir(dirname(__FILE__));

class CMS {
	
	public static $Config;
	public static $MySql;
	
	public static function Init() {
		
		require('../Library/MySql.php');
		include('../System/Configuration/Config.php');

		CMS::$MySql = new Db(CMS::$Config['mysql']);

		$Users = CMS::$MySql->query('SELECT id FROM users WHERE last_online >= UNIX_TIMESTAMP()-86400 AND (newpresent = 0 OR newpresent <= (UNIX_TIMESTAMP() - 86400))');

		foreach ($Users as $User) {
			
			if ($ID = CMS::$MySql->single('SELECT userid FROM cms_presents WHERE userid = :userid', Array('userid' => $User['id']))) {
				CMS::$MySql->query('UPDATE cms_presents SET presents = presents+1, vaultkeys = vaultkeys+3 WHERE userid = :userid', Array('userid' => $User['id']));
			}
			else {
				CMS::$MySql->query('INSERT INTO cms_presents (userid,presents,vaultkeys) VALUES (:userid,1,3)', Array('userid' => $User['id']));
			}
			
			CMS::$MySql->query('UPDATE users SET newpresent = UNIX_TIMESTAMP()-3600 WHERE id = :id', Array('id' => $User['id']));
		}
	}
}

CMS::Init();
?>