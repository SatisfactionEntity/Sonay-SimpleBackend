<?php

if (Users::$Session == true) {

    if ($_POST['action'] == 'deleteSession' && isset($_POST['session'])) {
        if (CMS::$MySql->single('SELECT userid FROM cms_sessions WHERE userid = :userid AND id = :session', array('userid' => Users::$Session->ID, 'session' => $_POST['session']))) {
            CMS::$MySql->query('DELETE FROM cms_sessions WHERE id = :session', array('session' => $_POST['session']));
            exit (CMS::$Lang['sessiondeleted']);
        }
    }

    if ($_POST['action'] == 'saveData') {
        if (isset($_POST['friendrequests'], $_POST['roominvites'], $_POST['following'])) {
            CMS::$MySql->query('UPDATE users_settings SET block_friendrequests=:friendrequests, block_roominvites=:roominvites, block_following=:following WHERE user_id=:userid', array('friendrequests' => $_POST['friendrequests'] == 'true' ? '1' : '0', 'roominvites' => $_POST['roominvites'] == 'true' ? '1' : '0', 'following' => $_POST['following'] == 'true' ? '1' : '0', 'userid' => Users::$Session->ID));
            exit;
        }
        if (isset($_POST['motto'])) {
            if (strlen($_POST['motto']) > 38 || Site::BadWord($_POST['motto'])) {
                $Data = 'motto';
            } else {
                CMS::$MySql->query('UPDATE users SET motto=:motto WHERE id=:userid', array('motto' => $_POST['motto'], 'userid' => Users::$Session->ID));
                $Data = '1';
                if (Users::$Session->Data['online'] != 0)
					$vartest = "lucas is een flikker";
                   // Site::RCON('updatemotto', array('user_id' => Users::$Session->ID, 'motto' => $_POST['motto']));
            }
            exit ($Data);
        }
        if (isset($_POST['home'], $_POST['radio'])) {
            CMS::$MySql->query('UPDATE users SET home=:home, radio=:radio WHERE id=:userid', array('home' => $_POST['home'] == 'true' ? '1' : '0', 'radio' => $_POST['radio'] == 'true' ? '1' : '0', 'userid' => Users::$Session->ID));
            exit;
        }
        if (isset($_POST['email'], $_POST['password'])) {
            if (empty($_POST['email']) || empty($_POST['password'])) {
                $Data['message'] = CMS::$Lang['enterallfields'];
            } else if (($Lastchange = CMS::$MySql->single("SELECT date FROM cms_logs WHERE userid = :userid AND action = 3 ORDER BY date DESC LIMIT 1", array('userid' => Users::$Session->ID))) > time() - CMS::$Config['cms']['timebetweenemailchange'] * 3600) {
                $Time = Site::secondsToTime($Lastchange - time() + CMS::$Config['cms']['timebetweenemailchange'] * 3600);
                $Data['message'] = str_ireplace(array('%hours%', '%minutes%'), array($Time['h'], $Time['m']), CMS::$Lang['emailchangetoofast']);
            } else if (!Users::ValidMail($_POST['email'])) {
                $Data['type'] = 'email';
                $Data['message'] = CMS::$Lang['invalidmail'];
            } else if (!Users::MailFree($_POST['email'])) {
                $Data['type'] = 'email';
                $Data['message'] = CMS::$Lang['mailinuse'];
            } else if (!Site::CorrectPassword($_POST['password'], Users::$Session->Data['password'])) {
                $Data['type'] = 'password';
                $Data['message'] = CMS::$Lang['invalidcurrentpass'];
            } else {
                CMS::$MySql->query('UPDATE users SET mail=:email WHERE id=:userid', array('email' => $_POST['email'], 'userid' => Users::$Session->ID));
                Site::LogCreator(Users::$Session->ID, 3, Users::$Session->Data['mail'], true);
                $Data['type'] = '1';
                $Data['message'] = CMS::$Lang['emailsaved'];
            }
            exit (json_encode($Data));
        }
        if (isset($_POST['oldpass'], $_POST['newpass'], $_POST['newpassrepeat'])) {
            if (Users::$Session->Data['password'] && empty($_POST['oldpass']) || empty($_POST['newpass']) || empty($_POST['newpassrepeat'])) {
                $Data['message'] = CMS::$Lang['enterallfields'];
            } else if (($Lastchange = CMS::$MySql->single("SELECT date FROM cms_logs WHERE userid = :userid AND action = 2 ORDER BY date DESC LIMIT 1", array('userid' => Users::$Session->ID))) > time() - CMS::$Config['cms']['timebetweenpasschange'] * 3600) {
                $Time = Site::secondsToTime($Lastchange - time() + CMS::$Config['cms']['timebetweenpasschange'] * 3600);
                $Data['message'] = str_ireplace(array('%hours%', '%minutes%'), array($Time['h'], $Time['m']), CMS::$Lang['passchangetoofast']);
            } else if (!Users::ValidPass($_POST['newpass'])) {
                $Data['type'] = 'newpassword';
                $Data['message'] = CMS::$Lang['invalidpass'];
            } else if ($_POST['newpass'] != $_POST['newpassrepeat']) {
                $Data['type'] = 'repassword';
                $Data['message'] = CMS::$Lang['passnotsame'];
            } else if (!Users::$Session->Data['password']) {
                $Data['valid'] = true;
            } else if (!Site::CorrectPassword($_POST['oldpass'], Users::$Session->Data['password'])) {
                $Data['type'] = 'oldpassword';
                $Data['message'] = CMS::$Lang['invalidcurrentpass'];
            } else if ($_POST['newpass'] == $_POST['oldpass']) {
                $Data['type'] = 'newpassword';
                $Data['message'] = CMS::$Lang['passsame'];
            } else {
                $Data['valid'] = true;
            }
            if (isset($Data['valid']))
            {
                CMS::$MySql->query('UPDATE users SET password=:password WHERE id=:userid', array('password' => Site::Hash($_POST['newpass']), 'userid' => Users::$Session->ID));
                Site::LogCreator(Users::$Session->ID, 2, '', true);
                $Data['message'] = CMS::$Lang['passsaved'];
            }
            exit (json_encode($Data));
        }
        if (isset($_POST['look'])) {
            if (substr($_POST['look'], 0, -9) == Users::$Session->Data['look']) {
                $Data['message'] = CMS::$Lang['looksame'];
            } else if (!Users::ValidFigure($_POST['look'])) {
                $Data['message'] = CMS::$Lang['invalidfigure'];
            }
            else
            {
                CMS::$MySql->query('UPDATE users SET look = :look, gender = :gender WHERE id = :userid', array('look' => substr($_POST['look'], 0, -9), 'gender' => substr($_POST['look'], -1), 'userid' => Users::$Session->ID));
                if (Users::$Session->Data['online'] != 0) {
                    Site::RCON('updateUser', array('user_id' => Users::$Session->ID, 'look' => substr($_POST['look'], 0, -9), 'gender' => substr($_POST['look'], -1)));
                }
                $Data['valid'] = true;
                $Data['message'] = CMS::$Lang['looksaved'];
            }
            exit (json_encode($Data));
        }
    }

    if ($_POST['action'] == 'viewDashboard') {
        $Data['texts'] = CMS::$Lang['dashboard'] . '|' . str_ireplace("%hotelname%", CMS::$Config['cms']['hotelname'], CMS::$Lang['dashboard2']);
        exit (json_encode($Data));
    }
    if ($_POST['action'] == 'viewHotel') {
        $Data = CMS::$MySql->row('SELECT block_following, block_friendrequests, block_roominvites FROM users_settings WHERE user_id = :userid', array('userid' => Users::$Session->ID));
        $Data['radio'] = Users::$Session->Data['radio'];
        $Data['texts'] = CMS::$Lang['hotel'] . '|' . CMS::$Lang['hotel2'] . '|' . CMS::$Lang['autosave'] . '|' . CMS::$Lang['friendrequest'] . '|' . CMS::$Lang['friendrequest2'] . '|' . CMS::$Lang['roominvite'] . '|' . CMS::$Lang['roominvite2'] . '|' . CMS::$Lang['follow'] . '|' . CMS::$Lang['follow2'];
        exit (json_encode($Data));
    }
    if ($_POST['action'] == 'viewGeneral') {
        $Data['texts'] = CMS::$Lang['general'] . '|' . CMS::$Lang['general2'] . '|' . CMS::$Lang['autosave'] . '|' . CMS::$Lang['motto'] . '|' . CMS::$Lang['motto2'] . '|' . CMS::$Lang['home'] . '|' . CMS::$Lang['home2'] . '|' . CMS::$Lang['radio'] . '|' . CMS::$Lang['radio2'];
        $Data['motto'] = Users::$Session->Data['motto'];
        $Data['home'] = Users::$Session->Data['home'];
        $Data['radio'] = Users::$Session->Data['radio'];
        exit (json_encode($Data));
    }
    if ($_POST['action'] == 'viewEmail') {
        $Data['texts'] = CMS::$Lang['email'] . '|' . CMS::$Lang['email2'] . '|' . CMS::$Lang['mail'] . '|' . CMS::$Lang['mail2'] . '|' . CMS::$Lang['pass'] . '|' . CMS::$Lang['pass2'] . '|' . CMS::$Lang['save'];
        $Data['email'] = Users::$Session->Data['mail'];
        exit (json_encode($Data));
    }
    if ($_POST['action'] == 'viewPassword') {
        if (!Users::$Session->Data['password'])
            $Data['password'] = CMS::$Lang['enternewpassword'];
        $Data['texts'] = CMS::$Lang['password'] . '|' . str_ireplace("%hotelname%", CMS::$Config['cms']['hotelname'], CMS::$Lang['password2']) . '|' . CMS::$Lang['passold'] . '|' . CMS::$Lang['passold2'] . '|' . CMS::$Lang['passnew'] . '|' . CMS::$Lang['passnew2'] . '|' . CMS::$Lang['passnew3'] . '|' . CMS::$Lang['passnew4'] . '|' . CMS::$Lang['save'];
        exit (json_encode($Data));
    }
    if ($_POST['action'] == 'viewSecurity') {
        $Data['texts'] = CMS::$Lang['security'] . '|' . CMS::$Lang['security2'] . '|' . CMS::$Lang['autosave'] . '|' . CMS::$Lang['googleauth'] . '|' . CMS::$Lang['googleauth2'];
        $Data['auth'] = Users::$Session->Data['twofactor'];
        exit (json_encode($Data));
    }
    if ($_POST['action'] == 'viewLook') {
        $Data['texts'] = CMS::$Lang['look'] . '|' . CMS::$Lang['look2'] . '|' . CMS::$Lang['looksave'];
        $Data['look'] = Users::$Session->Data['look'];
        $Data['gender'] = Users::$Session->Data['gender'];
        exit (json_encode($Data));
    }
    if ($_POST['action'] == 'viewSessions') {

        if (($Load = isset($_POST['load']) ? (int)$_POST['load'] : 0) == 0) {
            $Data['texts'] = CMS::$Lang['sessions'] . '|' . CMS::$Lang['sessions2'] . '|' . CMS::$Lang['ip'] . '|' . CMS::$Lang['sessionstarts'] . '|' . CMS::$Lang['sessionends'] . '|' . CMS::$Lang['action'] . '|' . CMS::$Lang['logout'];
            $Data['count'] = CMS::$MySql->single('SELECT COUNT(*) FROM cms_sessions WHERE userid = :userid AND expire > UNIX_TIMESTAMP()', array('userid' => Users::$Session->ID));
        }

        if ($Load >= 0 && $Load < 20 && $Sessions = CMS::$MySql->query('SELECT id, secret, ip, created, expire FROM cms_sessions WHERE userid = :userid AND expire > UNIX_TIMESTAMP() ORDER BY created DESC LIMIT '.$Load * 25 .',25', array('userid' => Users::$Session->ID))) {
            $i = 0;
            foreach ($Sessions as $Row) {
                $Data['sessions'][$i]['id'] = $Row['id'];
                $Data['sessions'][$i]['created'] = date('d-m-Y H:i', $Row['created']);
                $Data['sessions'][$i]['expire'] = date('d-m-Y H:i', $Row['expire']);
                $Data['sessions'][$i]['ip'] = substr($Row['ip'], 0, 15);
                $Data['sessions'][$i]['own'] = $Row['secret'] == $_COOKIE['simple_user_hash'] ? true : false;
                $i++;
            }
        }

        if (isset($Data)) {
            exit (json_encode($Data));
        }
    }
    if ($_POST['action'] == 'viewLogs') {

        if (($Load = isset($_POST['load']) ? (int)$_POST['load'] : 0) == 0) {
            $Data['texts'] = CMS::$Lang['logs'] . '|' . CMS::$Lang['logs2'] . '|' . CMS::$Lang['info'] . '|' . CMS::$Lang['date'] . '|' . CMS::$Lang['action'];
            $Data['count'] = CMS::$MySql->single('SELECT COUNT(*) FROM cms_logs WHERE userid = :userid', array('userid' => Users::$Session->ID));
        }

        if ($Load >= 0 && $Load < 20 && $Logs = CMS::$MySql->query('SELECT ip, action, data, date FROM cms_logs WHERE userid = :userid ORDER BY date DESC LIMIT '.$Load * 25 .',25', array('userid' => Users::$Session->ID))) {
            $i = 0;
            foreach ($Logs as $Row) {
                $Data['logs'][$i]['ip'] = $Row['ip'];
                $Data['logs'][$i]['action'] = CMS::$Lang['log'.$Row['action']];
                $Data['logs'][$i]['data'] = $Row['data'] ? isset(CMS::$Lang['logdata'.$Row['action']]) ? str_ireplace("%data%", $Row['data'], CMS::$Lang['logdata'.$Row['action']]) : $Row['data'] : CMS::$Lang['noextradata'];
                $Data['logs'][$i]['date'] = date('d-m-Y - H:i:s', $Row['date']);
                $i++;
            }
        }

        if (isset($Data)) {
            exit (json_encode($Data));
        }
    }
}