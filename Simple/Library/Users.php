<?php

class Users
{
    public static $Session = false;

    public static function CheckLogin($Session)
    {
        $Row = CMS::$MySql->row('
          SELECT cms_sessions.ip,a1.id,username,mail,password,rank,credits,motto,look,gender,online,ip_current,radio,home,twofactor,a6.id AS ipcheck,
                IFNULL(a2.amount,0) AS `0`,
                IFNULL(a3.amount,0) AS `5`
                FROM cms_sessions
                        LEFT JOIN users a1 ON a1.id = userid
                        LEFT JOIN users_currency a2 ON a2.user_id = userid AND a2.type = 0
                        LEFT JOIN users_currency a3 ON a3.user_id = userid AND a3.type = 5
                        LEFT JOIN bans a5 ON spoof = "0" AND a5.ban_expire > UNIX_TIMESTAMP() AND ((a5.user_id = userid AND a5.type = "account") OR (a5.ip = cms_sessions.ip AND (a5.type = "machine" OR a5.type = "ip" OR a5.type = "super")))
			LEFT JOIN cms_filter a6 ON a6.user_id = userid AND (a6.ip = "0.0.0.0" OR a6.ip = :ip2)
                WHERE secret = :secret AND (cms_sessions.ip = :ip OR spoof = "1") AND a5.id IS NULL', array('secret' => $Session, 'ip' => RemoteIp, 'ip2' => RemoteIp));

        if (!$Row['id'] || ($Row['rank'] >= 23 && !$Row['ipcheck'])) {
	    if (isset($Row['online']) && $Row['online'] == '1') {
                Site::RCON('disconnect', array('user_id' => $Row['id']));
            }
            self::KillSession($Session);
            Site::Stop('/index');
        }

        self::$Session = new Session($Row);
    }

	public static function GetSSO() {
		
		$SSO = Site::RandomCode();
		
		CMS::$MySql->query('UPDATE users SET ip_current = :ip, auth_ticket = :auth_ticket WHERE id = :userid', array('ip' => Users::$Session->Data['ip'], 'userid' => Users::$Session->Data['id'], 'auth_ticket' => $SSO));
		
		return $SSO;	
	}
	
    public static function CookieGenerator($ID, $Spoof = null)
    {
        $Time = time() + 604800;
        $Secret = Site::RandomCode();

        setcookie('simple_user_hash', $Secret, $Time, '/');
        CMS::$MySql->query('INSERT INTO cms_sessions (userid, secret, ip, created, expire, spoof) VALUES(:userid, :secret, :ip, :created, :expire, :spoof)', array('userid' => $ID, 'secret' => $Secret, 'ip' => $Spoof ? $Spoof : RemoteIp, 'created' => time(), 'expire' => $Time, 'spoof' => $Spoof ? "1" : "0"));
    }

    public static function Login($Name_Mail, $Password, $Auth, $Captcha)
    {
        if (CMS::$Config['cms']['captcha']) {
            if ($Logins = CMS::$Cache->get('logins-' . RemoteIp)) {
                if ($Logins >= CMS::$Config['cms']['captchamaxlogins']) {
                    if (!$Captcha || !Site::ValidCaptcha($Captcha)) {
                        $Data['message'] = CMS::$Lang['invalidcaptcha'];
                        return $Data;
                    }
                } else {
                    if ($Logins == CMS::$Config['cms']['captchamaxlogins'] - 1) {
                        $Data['captcha'] = true;
                    }
                    CMS::$Cache->increment('logins-' . RemoteIp);
                }
            } else {
                CMS::$Cache->set('logins-' . RemoteIp, 1, CMS::$Config['cms']['captchamaxloginstime']);
            }
        }

 	$Row = CMS::$MySql->row("SELECT users.id, users.username, users.password, users.rank, users.ip_current, users.twofactor, cms_filter.id AS ipcheck FROM users LEFT JOIN cms_filter ON cms_filter.user_id = users.id AND (cms_filter.ip = '0.0.0.0' OR cms_filter.ip = :ip) WHERE users.username = :username OR users.mail = :mail", array('ip' => RemoteIp, 'username' => $Name_Mail, 'mail' => $Name_Mail));

        if (!$Row || !Site::CorrectPassword($Password, $Row['password']) || $Row['twofactor'] && (!is_numeric($Auth) || strlen($Auth) != 6 || !Site::GoogleAuth('ValidLogin', $Row['twofactor'] . '|' . $Auth)) || CMS::$Config['cms']['staffonlyemail'] && $Row['rank'] > 1 && strtolower($Row['username']) == strtolower($Name_Mail)) {
            if (!$Auth && $Row['twofactor']) {
                $Data['auth'] = true;
            }
            $Data['message'] = $Row['twofactor'] ? CMS::$Lang['invalidloginorauth'] : CMS::$Lang['invalidlogin'];
            return $Data;
        }

        if ($Bancheck = Users::BanCheck(RemoteIp, $Row['id'])) {
            $Data['message'] = str_ireplace(array("%Hotelname%", "%bantype%", "%data_reason%", "%data_date%"), array(CMS::$Config['cms']['hotelname'], $Bancheck['type'], $Bancheck['ban_reason'], $Bancheck['ban_expire']), CMS::$Lang['banned']);
            return $Data;
        }

        if ($Row['rank'] >= 23 && !$Row['ipcheck']) {
            $Data['message'] = 'Oeps je IP-adres staat niet in de whitelist!';
            return $Data;
        }

        Users::CookieGenerator($Row['id']);

        $Data['valid'] = true;
        $Data['message'] = CMS::$Lang['loginsuccess'];

        return $Data;
    }

    public static function SocialLogin($Row)
    {
        if ($Bancheck = Users::BanCheck(RemoteIp, $Row['id'])) {
            $Data['response'] = str_ireplace(array("%Hotelname%", "%bantype%", "%data_reason%", "%data_date%"), array(CMS::$Config['cms']['hotelname'], $Bancheck['type'], $Bancheck['ban_reason'], $Bancheck['ban_expire']), CMS::$Lang['banned']);
            return $Data;
        }

        self::CookieGenerator($Row['id']);

        $Data['valid'] = true;
        $Data['response'] = CMS::$Lang['loginsuccess'];

        return $Data;
    }

    public static function KillSession($Session = null)
    {

	if (Users::$Session->Data['online'] == '1') {
                Site::RCON('disconnect', array('user_id' => Users::$Session->ID));
        }

        if ($Session) {
            CMS::$MySql->query('DELETE FROM cms_sessions WHERE secret = :session', array('session' => $Session));
        }

        setcookie('simple_user_hash', '', time() - 3600);
    }

    public static function ValidName($Name)
    {
        return (strlen($Name) >= 3 && strlen($Name) <= 16 && preg_match('/^[A-Za-z0-9_=?!@:,-]*$/', $Name));
    }

    public static function ValidTag($Tag)
    {
        return (strlen($Tag) > 0 && strlen($Tag) <= 20 && ctype_alnum($Tag));
    }

    public static function NameFree($Name)
    {
        return (CMS::$MySql->single("SELECT COUNT(*) FROM users WHERE username = :username", array('username' => $Name)) == 0);
    }

    public static function ValidMail($Mail)
    {
        return (preg_match("/^[a-zA-Z0-9_\.-]+@([a-zA-Z0-9]+([\-]+[a-zA-Z0-9]+)*\.)+[a-z]{2,7}$/i", $Mail)
            && strlen($Mail) >= 3 && strlen($Mail) <= 64);
    }

    public static function MailFree($Mail)
    {
        return (CMS::$MySql->single("SELECT COUNT(*) FROM users WHERE mail = :mail", array('mail' => $Mail)) == 0);
    }

    public static function ValidPass($Pass)
    {
        return (strlen($Pass) >= 6 && strlen($Pass) <= 32);
    }

    public static function ValidFigure($Figure)
    {
        if (strlen($Figure) >= 20 && strlen($Figure) <= 200 && strpos($Figure, 'hd-') !== false && strpos($Figure, 'lg-') !== false && substr_count($Figure, '=') == 1) {
            if (substr($Figure, -9) == '&gender=M') {
                return true;
            }
            if (substr($Figure, -9) == '&gender=F' && strpos($Figure, 'ch-') !== false) {
                return true;
            }
        }
        return false;
    }

    public static function ToMuchIP()
    {
        return (CMS::$MySql->single("SELECT COUNT(*) FROM users WHERE ip_register = :ip", array('ip' => RemoteIp)) >= CMS::$Config['cms']['maxaccountsperip']);
    }

    public static function CreateUniqueName($Name)
    {
        if ($List = CMS::$MySql->column('SELECT LOWER(username) FROM users WHERE username LIKE :username', array('username' => $Name.'%')))
        {
            $Check = strtolower($Name);
            $List = array_flip($List);
            for ($i = 2; ; $i++)
            {
                if(!isset($List[$Check.$i])) {
                    $Name = $Name.$i;
                    break;
                }
            }
        }
        return $Name;
    }

    public static function BanCheck($IP, $Userid = null)
    {
        if ($BanRow = CMS::$MySql->row('SELECT user_id,ban_reason,ban_expire FROM bans WHERE ban_expire > UNIX_TIMESTAMP() AND ((user_id = :userid AND type = "account") OR (ip = :ip AND (type = "machine" OR type = "ip" OR type = "super"))) LIMIT 1', array('userid' => $Userid, 'ip' => $IP))) {
            $BanRow['type'] = $Userid == $BanRow['user_id'] ? 'account' : 'ip';
            $BanRow['ban_expire'] = date('d-m-Y H:i:s', $BanRow['ban_expire']);
            $BanRow['ban_reason'] = $BanRow['ban_reason'] ? htmlspecialchars($BanRow['ban_reason']) : CMS::$Lang['defaultbanreason'];
            return $BanRow;
        }
        return false;
    }

    public static function AddSocialCheck($Name)
    {
        $Data['valid'] = false;

        if (Users::ToMuchIP()) {
            $Data['response'] = str_ireplace("%amount%", CMS::$Config['cms']['maxaccountsperip'], CMS::$Lang['maxaccountsreached']);
        }
        else if ($Bancheck = Users::BanCheck(RemoteIp)) {
            $Data['response'] = str_ireplace(array("%Hotelname%", "%bantype%", "%data_reason%", "%data_date%"), array(CMS::$Config['cms']['hotelname'], $Bancheck['type'], $Bancheck['ban_reason'], $Bancheck['ban_expire']), CMS::$Lang['banned']);
        }
        else if (!Users::ValidName($Name) || Site::BadWord($Name, false)) {
            $Data['response'] = CMS::$Lang['socialnameinvalid'];
        }
        else {
            $Data['valid'] = true;
        }

        return $Data;
    }

    public static function AddCheck($Name, $Mail, $Pass, $Pass2, $Figure, $Captcha)
    {
        $Data['valid'] = false;
        $Data['field'] = 'none';

        if (CMS::$Config['cms']['captcha'] && (!$Captcha || !Site::ValidCaptcha($Captcha))) {
            $Data['response'] = CMS::$Lang['invalidcaptcha'];
            $Data['return'] = '4';
        }
        else if (Users::ToMuchIP()) {
            $Data['response'] = str_ireplace("%amount%", CMS::$Config['cms']['maxaccountsperip'], CMS::$Lang['maxaccountsreached']);
            $Data['return'] = '4';
        }
        else if ($Bancheck = Users::BanCheck(RemoteIp)) {
            $Data['response'] = str_ireplace(array("%Hotelname%", "%bantype%", "%data_reason%", "%data_date%"), array(CMS::$Config['cms']['hotelname'], $Bancheck['type'], $Bancheck['ban_reason'], $Bancheck['ban_expire']), CMS::$Lang['banned']);
        }
        else if (!Users::ValidFigure($Figure)) {
            $Data['response'] = CMS::$Lang['invalidfigure'];
            $Data['return'] = '3';
        }
        else if (!Users::ValidName($Name)) {
            $Data['response'] = CMS::$Lang['invalidname'];
            $Data['field'] = 'username';
            $Data['return'] = '4';
        }
        else if (!Users::NameFree($Name)) {
            $Data['response'] = CMS::$Lang['nameinuse'];
            $Data['field'] = 'username';
            $Data['return'] = '4';
        }
        else if (!Users::ValidMail($Mail)) {
            $Data['response'] = CMS::$Lang['invalidmail'];
            $Data['field'] = 'mail';
            $Data['return'] = '1';
        }
        else if (!Users::MailFree($Mail)) {
            $Data['response'] = CMS::$Lang['mailinuse'];
            $Data['field'] = 'mail';
            $Data['return'] = '1';
        }
        else if (!Users::ValidPass($Pass)) {
            $Data['response'] = CMS::$Lang['invalidpass'];
            $Data['field'] = 'password';
            $Data['return'] = '2';
        }
        else if ($Pass != $Pass2) {
            $Data['response'] = CMS::$Lang['passnotsame'];
            $Data['field'] = 'password-repeat';
            $Data['return'] = '2';
        }
        else {
            $Data['valid'] = true;
        }

        return $Data;
    }

    public static function AddUser($Name, $Mail, $Pass, $Figure)
    {
        $Data = CMS::$MySql->insert('users', Array(
            'username' => $Name,
            'password' => $Pass ? Site::Hash($Pass) : '',
            'mail' => $Mail,
            'credits' => CMS::$Config['register']['credits'],
            'look' => substr($Figure, 0, -9),
            'gender' => substr($Figure, -1),
            'motto' => CMS::$Config['register']['motto'],
            'account_created' => time(),
            'last_online' => time(),
            'ip_current' => RemoteIp,
            'ip_register' => RemoteIp,
            'home_room' => CMS::$Config['register']['home_room'],
        ));

        if ($Data == 1)
        {
            $ID = CMS::$MySql->lastInsertId();

            CMS::$MySql->query("INSERT INTO users_currency (user_id, type, amount) VALUES (:userid,0,:amount), (:userid2,5,:amount2), (:userid3,103,:amount3)", array('userid' => $ID, 'amount' => CMS::$Config['register']['duckets'], 'userid2' => $ID, 'amount2' => CMS::$Config['register']['diamonds'], 'userid3' => $ID, 'amount3' => CMS::$Config['register']['crowns']));

            if (isset($_SESSION['referral']))
            {
                if (!CMS::$MySql->single('SELECT COUNT(*) FROM cms_referral LEFT JOIN users ON users.id = invited_id WHERE ip_register = :ip', array('ip' => RemoteIp))) {
                    CMS::$MySql->query('INSERT INTO cms_referral (target_id,invited_id) VALUES (:target,:invited)', array('target' => $_SESSION['referral'], 'invited' => $ID));
                }
                unset($_SESSION['referral']);
            }
            return $ID;
        }
        return false;
    }
}
