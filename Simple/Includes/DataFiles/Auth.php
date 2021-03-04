<?php

error_reporting(0);

if (Users::$Session == false) {

    if ($_POST['action'] == "getPlayerData" && isset($_POST['input']))
    {
        $Data['player'] = CMS::$MySql->row("SELECT look,twofactor FROM users WHERE username = :username OR mail = :mail", array('username' => $_POST['input'], 'mail' => $_POST['input']));

        if ($Data['player']) {
            $Data['player']['twofactor'] = (bool)$Data['player']['twofactor'];
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] == "FacebookLogin")
    {
        require_once 'Simple/Includes/Modules/autoload.php';

        $fb = new Facebook\Facebook([
            'app_id' => CMS::$Config['cms']['facebookappid'],
            'app_secret' => CMS::$Config['cms']['facebooksecret'],
            'default_graph_version' => 'v2.9',
        ]);

        $helper = $fb->getJavaScriptHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            $Data['response'] = 'Graph returned an error: ' . $e->getMessage();
            exit(json_encode($Data));
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            $Data['response'] = 'Facebook SDK returned an error: ' . $e->getMessage();
            exit(json_encode($Data));
        }

        if (!isset($accessToken)) {
            $Data['response'] = CMS::$Lang['socialcheckfailed'];
            exit(json_encode($Data));
        }

        $response = $fb->get('/me?fields=id,email,first_name,last_name', $accessToken);
        $User = $response->getGraphUser();

        if (!isset($User['email'])) {
            $Data['response'] = CMS::$Lang['socialemailnotfound'];
        }
        else if ($Player = CMS::$MySql->row("SELECT id, username, ip_current FROM users WHERE mail = :email", array('email' => $User['email'])))
        {
            $Data = Users::SocialLogin($Player);
        }
        else
        {
            $Data = Users::AddSocialCheck($Username = substr($User['first_name'].$User['last_name'], 0, 14));

            if ($Data['valid'])
            {
                if ($Add = Users::AddUser(strtolower(Users::CreateUniqueName($Username)), $User['email'], '', CMS::$Config['register']['avatar']))
                {
                    Users::CookieGenerator($Add);
                    $Data['response'] = CMS::$Lang['registersuccess'];
                }
                else
                {
                    $Data['valid'] = false;
                    $Data['response'] = CMS::$Lang['registererror'];
                }
            }
        }
        exit (json_encode($Data));
    }

    if ($_POST['action'] == "RegisterJson" && isset($_POST['username'], $_POST['password'], $_POST['retypedPassword'], $_POST['email'], $_POST['look']))
    {
        $Data = Users::AddCheck($_POST['username'], $_POST['email'], $_POST['password'], $_POST['retypedPassword'], $_POST['look'], isset($_POST['captcha']) ? $_POST['captcha'] : false);

        if ($Data['valid'])
        {
            if ($Add = Users::AddUser($_POST['username'], strtolower($_POST['email']), $_POST['password'], $_POST['look']))
            {
                Users::CookieGenerator($Add);
                $Data['response'] = CMS::$Lang['registersuccess'];
            }
            else
            {
                $Data['valid'] = false;
                $Data['response'] = CMS::$Lang['registererror'];
            }
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] == "Login" && isset($_POST['username'], $_POST['password']))
    {
        $Data = Users::Login($_POST['username'], $_POST['password'],isset($_POST['auth']) ? $_POST['auth'] : false, isset($_POST['captcha']) ? $_POST['captcha'] : false);

        exit (json_encode($Data));
    }

    if ($_POST['action'] == "sendMail" && isset($_POST['username']) && Users::$Session == false) {

        $CheckData = (int)CMS::$MySql->single('SELECT expire FROM cms_forgot_password WHERE triggeredby = :ip ORDER BY triggeredby DESC LIMIT 1', array('ip' => RemoteIp));

        if ($CheckData > time()) {
            $Time = Site::secondsToTime($CheckData - time());
            $Data['response'] = str_ireplace(array("%hour%", "%min%"), array($Time['h'], $Time['m']), CMS::$Lang['mailwaitip']);
            exit (json_encode($Data));
        }

        if (!$Row = CMS::$MySql->row('SELECT id,mail,username FROM users WHERE username = :username', array('username' => $_POST['username']))) {
            $Data['response'] = CMS::$Lang['playernotexists'];
            exit (json_encode($Data));
        }

        $Checkdata = (int)CMS::$MySql->single("SELECT expire FROM cms_forgot_password WHERE userid = :userid", array('userid' => $Row['id']));

        if ($CheckData > time()) {
            $Time = Site::secondsToTime($CheckData - time());
            $Data['response'] = str_ireplace(array("%hour%", "%min%"), array($Time['h'], $Time['m']), CMS::$Lang['mailwait']);
        } else {
            $Data = Site::SendMail('SendCode', $Row);
        }
        exit (json_encode($Data));
    }
}
