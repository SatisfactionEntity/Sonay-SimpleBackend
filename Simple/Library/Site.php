<?php

class Site
{
    public static function Stop($Redirect)
    {
        header('Location: ' . $Redirect);
        exit;
    }

    public static function StringEncoder($str) {
        return preg_replace('~..~', '%$0', strtoupper(unpack('H*', $str)[1]));
    }
	
	public static function GetOnline() {
		
		return round(CMS::$MySql->single('SELECT COUNT(*) FROM users WHERE online = "1"') / 10 * 12);
	}
	
    public static function GiveRewardsToUser($Userid, $Rewards)
    {
        foreach (explode(';', $Rewards) as $Row)
        {
            $Parms = explode(':', $Row);

            if (isset($Parms[2])) // Furni
            {
                for ($i = 0; $i < $Parms[2]; $i++) {
                    CMS::$MySql->query('INSERT INTO items (user_id, item_id) VALUES(:userid, :furni)', array('userid' => $Userid, 'furni' => $Parms[1]));
                }
            }
            else if (isset($Parms[1])) // Currency
            {
                CMS::$MySql->query('INSERT INTO users_currency (user_id, type, amount) VALUES(:userid, :type, :amount) ON DUPLICATE KEY UPDATE amount=amount+:amount2', array('userid' => $Userid, 'type' => $Parms[0], 'amount' => $Parms[1], 'amount2' => $Parms[1]));
            }
            else // Badge
            {
                CMS::$MySql->query('INSERT INTO users_badges (user_id, badge_code) VALUES(:userid, :badge) ON DUPLICATE KEY UPDATE badge_code=badge_code', array('userid' => $Userid, 'badge' => $Parms[0]));
            }
        }
    }

    public static function GoogleAuth($Action, $Parameters = '')
    {
        require_once 'Simple/Includes/Modules/autoload.php';
        $tfa = new RobThree\Auth\TwoFactorAuth(CMS::$Config['cms']['hotelname'] . ' Hotel');

        if ($Action == 'getData')
        {
            if (Users::$Session->Data['twofactor']) {
                $Data['enabled'] = true;
                $Data['image'] = $tfa->getQRCodeImageAsDataUri(Users::$Session->Data['username'], Users::$Session->Data['twofactor']);
                $Data['texts'] = CMS::$Lang['googleauth8'] . '|' . CMS::$Lang['googleauth9'] . '|' . CMS::$Lang['sixnumberscode'] . '|' . CMS::$Lang['close'] . '|' . CMS::$Lang['disable'];
            } else {
                $Data['enabled'] = false;
                if (!isset($_SESSION['secret'])) {
                    $_SESSION['secret'] = $tfa->createSecret(160);
                }
                $Data['image'] = $tfa->getQRCodeImageAsDataUri(Users::$Session->Data['username'], $_SESSION['secret']);
                $Data['texts'] = CMS::$Lang['googleauth4'] . '|' . CMS::$Lang['googleauth5'] . '|' . CMS::$Lang['close'] . '|' . CMS::$Lang['next'] . '|' . CMS::$Lang['googleauth6'] . '|' . str_ireplace('%code%', chunk_split($_SESSION['secret'], 4, ' '), CMS::$Lang['googleauth7']) . '|' . CMS::$Lang['sixnumberscode'] . '|' . CMS::$Lang['send'];
            }
            return $Data;
        }

        if ($Action == 'tryCode')
        {
            if (empty($_POST['code']) || empty($_POST['password'])) {
                $Data['message'] = CMS::$Lang['enterallfields'];
            } else if (!Site::CorrectPassword($_POST['password'], Users::$Session->Data['password'])) {
                $Data['message'] = CMS::$Lang['invalidcurrentpass'];
            } else if (!is_numeric($_POST['code']) || strlen($_POST['code']) != 6) {
                $Data['message'] = CMS::$Lang['googleauthinvalidcodelength'];
            } else if ($tfa->verifyCode($_SESSION['secret'], $_POST['code']) === true) {
                CMS::$MySql->query('UPDATE users SET twofactor = :twofactor WHERE id = :userid', array('twofactor' => $_SESSION['secret'], 'userid' => Users::$Session->ID));
                unset ($_SESSION['secret']);
                Site::LogCreator(Users::$Session->ID, 4);
                $Data['valid'] = true;
                $Data['message'] = CMS::$Lang['googleauthsuccess'];
            } else {
                $Data['message'] = CMS::$Lang['googleauthinvalidcode'];
            }
            return $Data;
        }

        if ($Action == 'deleteAuth')
        {
            if (empty($_POST['code']) || empty($_POST['password'])) {
                $Data['message'] = CMS::$Lang['enterallfields'];
            } else if (!Site::CorrectPassword($_POST['password'], Users::$Session->Data['password'])) {
                    $Data['message'] = CMS::$Lang['invalidcurrentpass'];
            } else if (!is_numeric($_POST['code']) || strlen($_POST['code']) != 6) {
                $Data['message'] = CMS::$Lang['googleauthinvalidcodelength'];
            } else if ($tfa->verifyCode(Users::$Session->Data['twofactor'], $_POST['code']) === true) {
                CMS::$MySql->query('UPDATE users SET twofactor = :twofactor WHERE id = :userid', array('twofactor' => '', 'userid' => Users::$Session->ID));
                Site::LogCreator(Users::$Session->ID, 5);
                $Data['valid'] = true;
                $Data['message'] = CMS::$Lang['googleauthdeletesuccess'];
            } else {
                $Data['message'] = CMS::$Lang['googleauthinvalidcode'];
            }
            return $Data;
        }

        if ($Action == 'ValidLogin') {
            $Data = explode('|', $Parameters);
            return $tfa->verifyCode($Data[0], $Data[1]) === true;
        }
    }

    public static function SendMail($Type, $User)
    {
        $Code = Site::RandomCode();

        if ($Type == 'SendCode') {
            $Title = 'Wachtwoord vergeten';
            $Message = "<table width='98%' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td align='center'><table border='0' cellpadding='0' cellspacing='0' width='595'><tbody><tr><td align='left' style='border-bottom:1px solid #aaaaaa;' height='70' valign='middle'><table border='0' cellpadding='0' cellspacing='0'><tbody><tr><td><img src='" . CMS::$Config['cms']['url'] . "/Simple/Assets/img/index/logo.png' alt='logo'></td></tr></tbody></table></td></tr><tr><td align='left' style='border-bottom:1px dashed #aaaaaa;' valign='middle'><table style='padding:0 0 10px 0;width:100%;' border='0' cellpadding='0' cellspacing='0'><tbody><tr><td valign='top'><p style='font-family:Verdana,Arial,sans-serif;font-size:20px;padding-top:15px;'>Hallo, " . $User['username'] . "!</p><p style='font-family:Verdana,Arial,sans-serif;font-size:12px;padding-bottom:5px;'>Je hebt op " . CMS::$Config['cms']['hotelname'] . " een wachtwoordreset aangevraagd. Natuurlijk willen wij jouw graag online terug zien, dus ontvang je deze mail van ons. In deze mail vind je een link. als je daar op klikt, krig je een nieuw wachtwoord verzonden. Schiet wel op. Hij is maar " . CMS::$Config['mail']['timebetweennextmail'] / 60 . " uur geldig.<br><br>Als dit vals alarm is, en jij hebt niet om een reset gevraagd, kan je deze mail gewoon negeren. Er gebeurt niks. Klik op de volgende link om je wachtwoord te resetten: <br><br><a href='" . CMS::$Config['cms']['url'] . "/forgot/password/" . $Code . "'>" . CMS::$Config['cms']['url'] . "/forgot/password/" . $Code . "</a><br><br>Tot snel!</p></td></tr></tbody></table></td></tr><tr><td align='left' style='border-bottom:1px solid #aaaaaa;' height='100' valign='middle'><table style='' border='0' cellpadding='0' cellspacing='0'><tbody><tr><td valign='middle'><table style='background-color:#51b708;height:50px;' height='50px;' cellpadding='0' cellspacing='0'><tbody><tr><td style='height:100%;vertical-align:middle;border:solid 2px #000000;' valign='middle'><p style='font-family:Verdana,Arial,sans-serif;font-weight:bold;font-size:18px;color:#ffffff;'><a style='text-decoration:none;padding:15px 20px;color:#ffffff;' href='" . CMS::$Config['cms']['url'] . "/forgot/password/" . $Code . "'>Naar " . CMS::$Config['cms']['hotelname'] . " Hotel</p></a></td></tr></tbody></table></td></tr></tbody></table></td></tr>";
        }
        if ($Type == 'NewPassword') {
            $Title = 'Je nieuwe wachtwoord';
            $Message = "<table width='98%' border='0' cellspacing='0' cellpadding='0'><tbody><tr><td align='center'><table border='0' cellpadding='0' cellspacing='0' width='595'><tbody> <tr> <td align='left' style='border-bottom:1px solid #aaaaaa;' height='70' valign='middle'> <table border='0' cellpadding='0' cellspacing='0'> <tbody> <tr> <td><img src='" . CMS::$Config['cms']['url'] . "/Simple/Assets/img/index/logo.png' alt='logo'></td></tr></tbody> </table> </td></tr><tr> <td align='left' style='border-bottom:1px dashed #aaaaaa;' valign='middle'> <table style='padding:0 0 10px 0;width:100%;' border='0' cellpadding='0' cellspacing='0'> <tbody> <tr> <td valign='top'> <p style='font-family:Verdana,Arial,sans-serif;font-size:20px;padding-top:15px;'>Hallo, " . $User['username'] . "!</p><p style='font-family:Verdana,Arial,sans-serif;font-size:12px;padding-bottom:5px;'>Je nieuwe wachtwoord is: <b>" . $Code . "</b><br><br>Tot snel!</p></td></tr></tbody> </table> </td></tr><tr> <td align='left' style='border-bottom:1px solid #aaaaaa;' height='100' valign='middle'> <table style='' border='0' cellpadding='0' cellspacing='0'> <tbody> <tr> <td valign='middle'> <table style='background-color:#51b708;height:50px;' height='50px;' cellpadding='0' cellspacing='0'> <tbody> <tr> <td style='height:100%;vertical-align:middle;border:solid 2px #000000;' valign='middle'> <p style='font-family:Verdana,Arial,sans-serif;font-weight:bold;font-size:18px;color:#ffffff;'><a style='text-decoration:none;padding:15px 20px;color:#ffffff;' href='" . CMS::$Config['cms']['url'] . "/index'>Naar " . CMS::$Config['cms']['hotelname'] . " Hotel</p></a></td></tr></tbody></table></td></tr></tbody></table></td></tr>";
        }

        require_once 'Simple/Includes/Modules/autoload.php';

        $email = new PHPMailer;

        $email->isSMTP();
        $email->Host = CMS::$Config['mail']['smtphost'];
        $email->SMTPAuth = CMS::$Config['mail']['smtpauth'];
        $email->Username = CMS::$Config['mail']['smtpusername'];
        $email->Password = CMS::$Config['mail']['smtppassword'];
        $email->SMTPSecure = CMS::$Config['mail']['smtppsecure'];
        $email->Port = CMS::$Config['mail']['smtpport'];

        $email->setFrom(CMS::$Config['mail']['sender'], CMS::$Config['cms']['hotelname']);
        $email->addAddress($User['mail'], $User['username']);
        $email->isHTML(true);

        $email->Subject = CMS::$Config['cms']['hotelname'] . ' Hotel: ' . $Title;
        $email->Body = $Message;

        if (!$email->send()) {
            $Data['response'] = CMS::$Lang['cantsendmail'];
        } else {
            $Data['valid'] = true;
            if ($Type == 'SendCode') {
                $Time = time() + CMS::$Config['mail']['timebetweennextmail'] * 60;
                CMS::$MySql->query('INSERT INTO cms_forgot_password (userid, code, expire, triggeredby) VALUES(:userid, :code, :expire, :ip) ON DUPLICATE KEY UPDATE code=:code2, expire=:expire2, triggeredby=:ip2', array('userid' => $User['id'], 'code' => $Code, 'expire' => $Time, 'ip' => RemoteIp, 'code2' => $Code, 'expire2' => $Time, 'ip2' => RemoteIp));
                Site::LogCreator($User['id'], 7, $User['mail']);
                $Data['response'] = str_ireplace("%player%", $User['username'], CMS::$Lang['mailsended']);
            }
            if ($Type == 'NewPassword') {
                CMS::$MySql->query('DELETE FROM cms_forgot_password WHERE userid = :userid', array('userid' => $User['id']));
                CMS::$MySql->query('UPDATE users SET password = :pass WHERE id = :userid', array('pass' => Site::Hash($Code), 'userid' => $User['id']));
                Site::LogCreator($User['id'], 10);
                $Data['response'] = CMS::$Lang['mailsendedpassword'];
            }
        }
        return $Data;
    }

    public static function LogCreator($Userid, $Action, $Data = '', $Mail = false)
    {
        CMS::$MySql->query('INSERT INTO cms_logs (userid, action, data, date, ip) VALUES(:userid, :action, :data, :time, :ip)', array('userid' => $Userid, 'action' => $Action, 'data' => $Data, 'time' => time(), 'ip' => RemoteIp));
    }

    public static function secondsToTime($inputSeconds)
    {
        $secondsInAMinute = 60;
        $secondsInAnHour = 60 * $secondsInAMinute;
        $secondsInADay = 24 * $secondsInAnHour;

        $days = floor($inputSeconds / $secondsInADay);

        $hourSeconds = $inputSeconds % $secondsInADay;
        $hours = floor($hourSeconds / $secondsInAnHour);

        $minuteSeconds = $hourSeconds % $secondsInAnHour;
        $minutes = floor($minuteSeconds / $secondsInAMinute);

        $remainingSeconds = $minuteSeconds % $secondsInAMinute;
        $seconds = ceil($remainingSeconds);

        $obj = array(
            'd' => (int)$days,
            'h' => (int)$hours,
            'm' => (int)$minutes,
            's' => (int)$seconds,
        );
        return $obj;
    }

    public static function getCurrencyStats()
    {
        if (!$Currency = CMS::$Cache->get('allcurrencystats')) {
            if ($Currencies = CMS::$MySql->query("SELECT type,amount,username,look FROM((SELECT user_id, type, amount FROM users_currency WHERE NOT EXISTS (SELECT rank FROM users WHERE users.id = users_currency.user_id AND rank > 12) AND type = '0' ORDER BY amount DESC LIMIT 20) UNION (SELECT user_id, type, amount FROM users_currency WHERE NOT EXISTS (SELECT rank FROM users WHERE users.id = users_currency.user_id AND rank > 12) AND type = '5' ORDER BY amount DESC LIMIT 20)) AS users_currency LEFT JOIN users ON users.id = user_id ORDER BY type,amount DESC")) {
                $i = 0;
                $last = '';
                foreach ($Currencies as $Row) {
                    if ($last != $Row['type']) {
                        $i = 0;
                        $last = $Row['type'];
                    }
                    $Currency[$Row['type']][$i]['username'] = $Row['username'];
                    $Currency[$Row['type']][$i]['look'] = $Row['look'];
                    $Currency[$Row['type']][$i]['amount'] = $Row['amount'] . ' ' . CMS::$Lang[$Row['type']];
                    $i++;
                }
                CMS::$Cache->set('allcurrencystats', $Currency ? $Currency : 'none');
            }
        }
        return $Currency;
    }

    public static function GetUsersOnline()
    {
        if (!$Data = CMS::$Cache->get('online')) {
            $Data = round(CMS::$MySql->single("SELECT COUNT(*) FROM users WHERE online = '1'") /10 * 12);
            CMS::$Cache->set('online', $Data, 30);
        }
        return $Data;
    }

    public static function BadWord($Word, $CheckCharacters = true)
    {
        $Word = str_replace(' ', '', $Word);

        if ($CheckCharacters) {

            foreach (CMS::$MySql->query("SELECT * FROM cms_wordfilter_characters") as $Row) {
                $Chars[] = $Row['character'];
                $Change[] = $Row['replacement'];
            }

            $Word = str_ireplace($Chars, $Change, $Word);
        }

        foreach (CMS::$MySql->query("SELECT `key` FROM wordfilter") as $Row) {
            if (stripos($Word, $Row['key']) !== false) return true;
        }

        return false;
    }

    public static function ValidCaptcha($Captcha)
    {
        $Captchacheck = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . CMS::$Config['cms']['captchaprivatekey'] . '&response=' . $Captcha));
        return $Captchacheck->success == 1;
    }

    public static function Hash($Password)
    {
        return password_hash($Password, PASSWORD_BCRYPT);
    }

    public static function CorrectPassword($Password, $Hash)
    {
        if ($Hash && password_verify($Password, $Hash)) {
            return true;
        }
        return false;
    }

    public static function RandomCode()
    {
        return bin2hex(random_bytes(16));
    }

    public static function RCON($key, $content)
    {
        $data['key'] = $key;
        $data['data'] = $content;
        $data = json_encode($data);

        $socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);

        $out = '';

        if ($socket === false) {
            $out = "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
        } else if ($result = socket_connect($socket, CMS::$Config['cms']['mushost'], CMS::$Config['cms']['musport']) === false) {
            $out = "socket_connect() failed.\nReason: ($result) " . socket_strerror(socket_last_error($socket)) . "\n";
        } else if (socket_write($socket, $data, strlen($data)) === false) {
            $out = socket_strerror(socket_last_error($socket));
        }
        return $out;
    }
}
