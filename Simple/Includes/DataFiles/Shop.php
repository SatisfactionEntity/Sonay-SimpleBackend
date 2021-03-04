<?php

if (Users::$Session == true)
{
    if ($_POST['action'] == 'viewValuePage' && isset($_POST['id']))
    {

        $Data['values'] = CMS::$MySql->query('SELECT display_name,item_name,price FROM cms_values WHERE category_id = :id ORDER BY order_num,price ASC', array('id' => $_POST['id']));

        if (!$Data['values']) {
            $Data['message'] = CMS::$Lang['categorynofurni'];
        }

        $Data['furniiconmap'] = CMS::$Config['cms']['furniiconmap'];

        exit (json_encode($Data));
    }

    if ($_POST['action'] == 'makeVoucher' && isset($_POST['data']))
    {
        if (empty($_POST['data'])) {
            $Data['message'] = CMS::$Lang['voucherenterfield'];
        }
        else if (Users::$Session->Data['online'] != 0) {
            $Data['message'] = CMS::$Lang['vouchermakelogout'];
        }
        else
        {
			
            if (substr_count($_POST['data'], ';') > 1)
                return;
			
            $Total = 0;
            foreach (explode(';', $_POST['data']) as $Row)
            {
				
                if (substr_count($Row, ':') != 1 || !ctype_digit(($Parms = explode(':', $Row))[1]) || ($Parms[0] != '0' && $Parms[0] != '5'))
                    return;

				
                $Currency[$Parms[0]] = $Parms[1];
                $Total += $Parms[1];

                if (Users::$Session->Data[$Parms[0]] < $Parms[1] + ($Parms[0] == '5' ? CMS::$Config['cms']['vouchercreatecost'] : 0)) {
                    $Data['message'] = str_ireplace('%currency%', CMS::$Lang[$Parms[0]], CMS::$Lang['vouchernotenoughcurrency']);
                    break;
                }
            }
        }

        if (!isset($Data['message']))
        {
            if ($Total < CMS::$Config['cms']['voucherminimalamount'])
            {
                $Data['message'] = str_ireplace('%amount%', CMS::$Config['cms']['voucherminimalamount'], CMS::$Lang['vouchernotenoughtotal']);
            }
            else
            {
                foreach ($Currency as $type => $amount) {
                    CMS::$MySql->query("INSERT INTO users_currency (user_id, type, amount) VALUES(:userid, :type, 0) ON DUPLICATE KEY UPDATE amount=amount-:amount", array('userid' => Users::$Session->ID, 'type' => $type, 'amount' => $amount + ($type == '5' ? CMS::$Config['cms']['vouchercreatecost'] : 0)));
                }

                $Code = rtrim(chunk_split(Site::RandomCode(), 4, '-'), '-');
                $Expire = time() + CMS::$Config['cms']['vouchertimetillexpire'] * 86400;
                CMS::$MySql->query('INSERT INTO cms_vouchers (code,rewards,expire,amount,added_by) VALUES (:code,:rewards,:expire,1,:userid)', array('code' => $Code, 'rewards' => $_POST['data'], 'expire' => $Expire, 'userid' => Users::$Session->ID));

                $Data['texts'] = CMS::$Lang['vouchercreated'] . '|' . str_ireplace(array('%code%', '%time%'), array($Code, date('d-m-Y - H:i:s', $Expire)), CMS::$Lang['vouchercreated2']) . '|' . CMS::$Lang['close'];
            }
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] == 'claimVoucher' && isset($_POST['code']))
    {
        $Voucher = CMS::$MySql->row('SELECT id,rewards,amount,(SELECT COUNT(*) FROM cms_vouchers_claimed WHERE voucher_id = id) AS claimamount,(SELECT COUNT(*) FROM cms_vouchers_claimed WHERE voucher_id = id AND user_id = :userid) AS claimed,expire FROM cms_vouchers WHERE code = :code', array('userid' => Users::$Session->ID, 'code' => $_POST['code']));

        if (!$Voucher['id']) {
            $Data['message'] = CMS::$Lang['vouchernotfound'];
        }
        else if ($Voucher['claimed'] == 1) {
            $Data['message'] = CMS::$Lang['voucheralreadyclaimed'];
        }
        else if ($Voucher['claimamount'] >= $Voucher['amount']) {
            $Data['message'] = str_ireplace('%amount%', $Voucher['amount'], CMS::$Lang['vouchertoomuchclaimed']);
        }
        else if ($Voucher['expire'] < time()) {
            $Data['message'] = str_ireplace('%time%', date('d-m-Y', $Voucher['expire']), CMS::$Lang['voucherexpired']);
        }
        else if (Users::$Session->Data['online'] != 0) {
            $Data['message'] = CMS::$Lang['voucherclaimlogout'];
        }
        else
        {
            CMS::$MySql->query('INSERT INTO cms_vouchers_claimed (user_id,voucher_id) VALUES (:userid,:id)', array('userid' => Users::$Session->ID, 'id' => $Voucher['id']));

            Site::GiveRewardsToUser(Users::$Session->ID, $Voucher['rewards']);

            $Data['rewards'] = $Voucher['rewards'];
            $Data['config'] = CMS::$Config['cms']['furniiconmap'] . '|' . CMS::$Config['cms']['badgemap'];
            $Data['texts'] = CMS::$Lang['congrats'] . '|' . str_ireplace('%code%', $_POST['code'], CMS::$Lang['vouchercontains']) . '|' . CMS::$Lang['close'];
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] == 'claimReferralReward')
    {
        $Present = CMS::$MySql->row('SELECT level,friends_needed,rewards FROM cms_referral_rewards t1 WHERE friends_needed <= (SELECT COUNT(*) FROM cms_referral WHERE target_id = :userid) AND NOT EXISTS (SELECT 1 FROM cms_referral_claimed t2 WHERE t2.user_id = :userid2 AND t1.level = t2.reward_id) ORDER BY level ASC LIMIT 1', array('userid' => Users::$Session->ID, 'userid2' => Users::$Session->ID));

        if (!$Present)
            return;

        if (Users::$Session->Data['online'] != 0) {
            $Data['message'] = CMS::$Lang['logoutpresentclaim'];
        }
        else
        {
            CMS::$MySql->query('INSERT INTO cms_referral_claimed (user_id,reward_id) VALUES (:userid,:level)', array('userid' => Users::$Session->ID, 'level' => $Present['level']));

            Site::GiveRewardsToUser(Users::$Session->ID, $Present['rewards']);

            $Data['rewards'] = $Present['rewards'];
            $Data['config'] = CMS::$Config['cms']['furniiconmap'] . '|' . CMS::$Config['cms']['badgemap'];
            $Data['texts'] = CMS::$Lang['congrats'] . '|' . CMS::$Lang['presentcontains'] . '|' . str_ireplace('%amount%', $Present['friends_needed'], CMS::$Lang['invitefriendsfor']) . '|' . CMS::$Lang['close'];
        }
        exit (json_encode($Data));
    }

    if ($_POST['action'] == 'changeName' && isset($_POST['username'], $_POST['password'])) {
        if (empty($_POST['username']) || empty($_POST['password'])) {
            $Data['message'] = CMS::$Lang['enterallfields'];
        } else if (($Lastchange = CMS::$MySql->single("SELECT date FROM cms_logs WHERE userid = :userid AND action = 6 ORDER BY date DESC LIMIT 1", array('userid' => Users::$Session->ID))) > time() - CMS::$Config['cms']['timebetweennamechange'] * 86400) {
            $Time = Site::secondsToTime($Lastchange - time() + CMS::$Config['cms']['timebetweennamechange'] * 86400);
            $Data['message'] = str_ireplace(array('%days%', '%hours%'), array($Time['d'], $Time['h']), CMS::$Lang['namechangewait']);
        } else if (Users::$Session->Data['5'] < CMS::$Config['cms']['namechangecost']) {
            $Data['message'] = CMS::$Lang['namechangenotenough'];
        } else if (Users::$Session->Data['online'] != 0) {
        $Data['message'] = CMS::$Lang['namechangelogout'];
        } else if (!Users::ValidName($_POST['username']) || Site::BadWord($_POST['username'], false)) {
            $Data['message'] = CMS::$Lang['invalidname'];
        } else if (!Users::NameFree($_POST['username'])) {
            $Data['message'] = CMS::$Lang['nameinuse'];
        } else if (!Site::CorrectPassword($_POST['password'], Users::$Session->Data['password'])) {
            $Data['message'] = CMS::$Lang['invalidcurrentpass'];
        } else {
            CMS::$MySql->query("INSERT INTO users_currency (user_id, type, amount) VALUES(:userid, 5, 0) ON DUPLICATE KEY UPDATE amount=amount-:amount", array('userid' => Users::$Session->ID, 'amount' => CMS::$Config['cms']['namechangecost']));
            CMS::$MySql->query("UPDATE users SET username = :username WHERE id = :userid", array('username' => $_POST['username'], 'userid' => Users::$Session->ID));
            Site::LogCreator(Users::$Session->ID, 6, Users::$Session->Data['username'], true);
            $Data['valid'] = true;
            $Data['message'] = str_ireplace("%username%", $_POST['username'], CMS::$Lang['namechangesuccess']);
        }
        exit (json_encode($Data));
    }

    if ($_POST['action'] == "buyBadges" && isset($_POST['badges'])) {

        $Parms = substr_count($_POST['badges'], ';');

        if ($Parms > 14)
            return;

        if (Users::$Session->Data['online'] != 0) {
            $Data['message'] = CMS::$Lang['badgesbuylogout'];
            exit (json_encode($Data));
        }

        $Mylist = explode(';', $_POST['badges']);

        $Badges = CMS::$MySql->query('SELECT badge,price FROM cms_badges WHERE id in (:' . implode(',:', array_keys($Mylist)) . ')', $Mylist);

        $Count = count($Badges);

        if ($Count != $Parms+1)
            return;

        $Price = 0;
        for ($i = 0; $i < $Count; $i++) {
            $Price += $Badges[$i]['price'];
        }

        $Price = round($Price / 100 * (100 - (($Count - 1) * CMS::$Config['cms']['discountperitem'] > CMS::$Config['cms']['maxdiscount'] ? CMS::$Config['cms']['maxdiscount'] : ($Count - 1) * CMS::$Config['cms']['discountperitem'])));

        if (Users::$Session->Data['5'] < $Price)
            return;

        CMS::$MySql->query("INSERT INTO users_currency (user_id, type, amount) VALUES(:userid, 5, 0) ON DUPLICATE KEY UPDATE amount=amount-:amount", array('userid' => Users::$Session->ID, 'amount' => $Price));

        for ($i = 0; $i < $Count; $i++) {
            CMS::$MySql->query('INSERT INTO users_badges (user_id, badge_code) VALUES(:userid, :badge) ON DUPLICATE KEY UPDATE badge_code=badge_code', array('userid' => Users::$Session->ID, 'badge' => $Badges[$i]['badge']));
        }

        Site::LogCreator(Users::$Session->ID, 8, $_POST['badges']);

        $Data['valid'] = true;
        $Data['message'] = CMS::$Lang['badgesbuysuccess'];

        exit (json_encode($Data));
    }

    if ($_POST['action'] == "getBadgePage" && isset($_POST['id']))
    {
        if ($_POST['id'] == '0') {
            $Data['pages'] = CMS::$MySql->query('SELECT `id`,`name`,`title`,`default` FROM cms_badges_categories ORDER BY order_num ASC');
            foreach ($Data['pages'] as $Row) {
                if ($Row['default'] == '1') {
                    $Default = $Row['id'];
                }
            }
        }
        $Data['config'] = CMS::$Config['cms']['badgemap'] . '|' . CMS::$Config['cms']['discountperitem'] . '|' . CMS::$Config['cms']['maxdiscount'] . '|' . CMS::$Lang['add'] . '|' . CMS::$Lang['delete'] . '|' . CMS::$Lang['got'] . '|' . CMS::$Lang['selectbadge'] . '|' . CMS::$Lang['notenoughdiamonds'] . '|' . CMS::$Lang['maxbadgesreached'];
        $Data['badges'] = CMS::$MySql->query('SELECT cms_badges.id,`badge`,`desc`,`price`,users_badges.user_id FROM cms_badges LEFT JOIN users_badges ON users_badges.badge_code = badge AND users_badges.user_id = :userid WHERE category_id = :id ORDER BY users_badges.user_id,order_num ASC', array('userid' => Users::$Session->ID, 'id' => isset($Default) ? $Default : $_POST['id']));
        exit (json_encode($Data));
    }

    if ($_POST['action'] == "buyPackage" && isset($_POST['id'])) {

        if ($Present = CMS::$MySql->row('SELECT `id`,`content`,`currency_type`,`price` FROM cms_packages WHERE enabled = 1 AND id = :id', array('id' => $_POST['id']))) {
            if (Users::$Session->Data['online'] != 0) {
                $Data['message'] = CMS::$Lang['packagebuyonline'];
            } else if (Users::$Session->Data[$Present['currency_type']] < $Present['price']) {
                $Data['message'] = str_ireplace('%currency%', CMS::$Lang[$Present['currency_type']], CMS::$Lang['packagenotenoughcurrency']);
            }
            else
            {
                CMS::$MySql->bindMore(array('userid' => Users::$Session->ID, 'type' => $Present['currency_type'], 'amount' => $Present['price']));
                CMS::$MySql->query("INSERT INTO users_currency (user_id, type, amount) VALUES(:userid, :type, 0) ON DUPLICATE KEY UPDATE amount=amount-:amount");

                Site::GiveRewardsToUser(Users::$Session->ID, $Present['content']);
                Site::LogCreator(Users::$Session->ID, 9, $Present['id']);

                $Data['valid'] = true;
                $Data['message'] = CMS::$Lang['packagebuysuccess'];
            }
        } else {
            $Data['message'] = CMS::$Lang['packagecantbuy'];
        }
        exit (json_encode($Data));
    }
}