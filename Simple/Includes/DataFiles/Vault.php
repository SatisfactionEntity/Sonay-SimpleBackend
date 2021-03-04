<?php

if (Users::$Session == true) {

    if ($_POST['action'] == 'tryKey' && isset($_POST['code']) && is_numeric($_POST['code'])) {

        if (Users::$Session->Data['online'] != 0) {
            $Data['message'] = CMS::$Lang['vaultlogout'];
        }
        else
        {
            $Keys = (int)CMS::$MySql->single('SELECT vaultkeys FROM cms_presents WHERE userid = :userid', array('userid' => Users::$Session->ID));

            if ($Keys > 0) {

                CMS::$MySql->query('UPDATE cms_presents SET vaultkeys = vaultkeys-1 WHERE userid = :userid', array('userid' => Users::$Session->ID));

                $Code = CMS::$MySql->row("SELECT id,type,price,code,cracker_id FROM cms_vault WHERE code = :code", array('code' =>$_POST['code']));

                if ($Code['cracker_id']) {
                    $Data['message'] = CMS::$Lang['vaultalreadycracked'];
                }
                else if ($Code['code'])
                {
                    CMS::$MySql->query("UPDATE cms_vault SET cracker_id = :userid WHERE code = :code", array('userid' => Users::$Session->ID, 'code' => $Code['code']));

                    if ($Code['type'] == 'furni') {
                        CMS::$MySql->query("INSERT INTO items (user_id, item_id) VALUES(:userid, :furni)", array('userid' => Users::$Session->ID, 'furni' => explode(';', $Code['price'])[1]));
                    }
                    if ($Code['type'] == 'badge') {
                        CMS::$MySql->query("INSERT INTO users_badges (user_id, badge_code) VALUES(:userid, :badge) ON DUPLICATE KEY UPDATE badge_code=badge_code", array('userid' => Users::$Session->ID, 'badge' => $Code['price']));
                    }
                    if ($Code['type'] == 'diamonds') {
                        CMS::$MySql->query("INSERT INTO users_currency (user_id, type, amount) VALUES(:userid, 5, :amount) ON DUPLICATE KEY UPDATE amount=amount+:amount2", array('userid' => Users::$Session->ID, 'amount' => $Code['price'], 'amount2' => $Code['price']));
                    }

                    $Data['cracked'] = true;
                    $Data['id'] = $Code['id'];
                    $Data['cracker'] = Users::$Session->Data['username'];
                    $Data['yes'] = CMS::$Lang['yes'];
                    $Data['message'] = CMS::$Lang['vaultcracked'];
                }
                else {
                    $Data['message'] = CMS::$Lang['vaultcrackfailed'];
                }
                $Data['amount'] = str_ireplace("%amount%", --$Keys, CMS::$Lang['vaulttimes']);
            }
            else {
                $Data['message'] = CMS::$Lang['vaultnokeys'];
            }
        }
        exit (json_encode($Data));
    }
}