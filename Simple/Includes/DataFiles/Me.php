<?php

if (Users::$Session == true) {

    if ($_POST['action'] == 'getAuthData') {
        $Data = Site::GoogleAuth('getData');
        exit (json_encode($Data));
    }

    if ($_POST['action'] == 'tryAuthCode' && isset($_POST['code'], $_POST['password'], $_SESSION['secret']) && !Users::$Session->Data['twofactor'] ) {
        $Data = Site::GoogleAuth('tryCode');
        exit (json_encode($Data));
    }

    if ($_POST['action'] == 'deleteAuth' && isset($_POST['code'], $_POST['password']) && Users::$Session->Data['twofactor']) {
        $Data = Site::GoogleAuth('deleteAuth');
        exit (json_encode($Data));
    }

    if ($_POST['action'] == "Logout") {
        Users::KillSession($_COOKIE['simple_user_hash']);
        exit('success');
    }

    if ($_POST['action'] == "claimGift") {

        $Presents = (int)CMS::$MySql->single('SELECT presents FROM cms_presents WHERE userid = :userid', array('userid' => Users::$Session->ID));

        if ($Presents > 0) {

            $Data['presentsleft'] = --$Presents;

            $Price = rand(CMS::$Config['cms']['minpresentreward'], CMS::$Config['cms']['maxpresentreward']);

            CMS::$MySql->query("INSERT INTO users_currency (user_id, type, amount) VALUES(:userid, 5, :amount) ON DUPLICATE KEY UPDATE amount=amount+:amount2", array('userid' => Users::$Session->ID, 'amount' => $Price, 'amount2' => $Price)); // be sure
            CMS::$MySql->query("UPDATE cms_presents SET presents = presents-1 WHERE userid = :userid", array('userid' => Users::$Session->ID));

            $Data['reward'] = $Price;
            $Data['texts'] = CMS::$Lang['giftheader'] . '|' . str_ireplace('%amount%', $Price, CMS::$Lang['giftreceivedbody']) . '|' . CMS::$Lang['giftclose'] . '|' . ($Data['presentsleft'] == 0 ? CMS::$Lang['giftcomeback'] : str_ireplace('%amount%', $Data['presentsleft'], CMS::$Lang['giftreceivedopennew']));

            exit (json_encode($Data));
        }
        return;
    }

    if ($_POST['action'] == "getGiftData" && isset($_POST['clicked'])) {

        $Data['presents'] = (int)CMS::$MySql->single('SELECT presents FROM cms_presents WHERE userid = :userid', array('userid' => Users::$Session->ID));

        if ($Data['presents'] > 0) {
            $Data['texts'] = CMS::$Lang['giftheader'] . '|' . str_ireplace(array("%username%", "%hotelname%"), array(Users::$Session->Data['username'], CMS::$Config['cms']['hotelname']), CMS::$Lang['giftbody']) . '|' . str_ireplace('%amount%', $Data['presents'], CMS::$Lang['giftsremaining']) . '|' . CMS::$Lang['giftclaim'] . '|' . CMS::$Lang['giftclose'];
        } else if ($_POST['clicked'] == 'yes' && $Data['presents'] == 0) {
            $Data['texts'] = CMS::$Lang['allgiftsclaimedheader'] . '|' . str_ireplace("%username%", Users::$Session->Data['username'], CMS::$Lang['allgiftsclaimedbody']) . '|' . CMS::$Lang['allgiftsclaimedfooter'] . '|' . '' . '|' . CMS::$Lang['giftclose'];
        }

        exit (json_encode($Data));
    }
}