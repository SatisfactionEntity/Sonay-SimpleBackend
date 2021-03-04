<?php

if (Users::$Session == true)
{

    if ($_POST['action'] === 'addiptofilter' && isset($_POST['ip'],$_POST['username']) && Users::$Session->HasPermission(CMS::$Config['manage']['filter']))
    {
        if (!filter_var($_POST['ip'], FILTER_VALIDATE_IP)) {
            $Data['message'] = 'Oeps! ongeldig ip!';
        }

        else if (!Users::ValidName($_POST['username'])) {
            $Data['message'] = CMS::$Lang['invalidname'];
        }

        else if (!$Player = CMS::$MySql->row('SELECT id,rank FROM users WHERE username = :username', array('username' => $_POST['username']))) {
            $Data['message'] = 'Oeps! deze speler bestaat niet!';
        }

        else if ($Player['rank'] < 23) {
            $Data['message'] = 'Oeps! deze speler is geen staff!';
        }

        else {

            CMS::$MySql->query('INSERT INTO cms_filter (user_id,ip,added_by) VALUES (:userid,:ip,:added_by)', array('userid' => $Player['id'], 'ip' => $_POST['ip'], 'added_by' => Users::$Session->Data['id']));

            $Data['valid'] = true;
            $Data['message'] = $_POST['ip'] . ' is succesvol aan de ip filter toegevoegd!';
            $Data['id'] = CMS::$MySql->lastInsertId();
            $Data['added_by'] = Users::$Session->Data['username'];
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'removeipfromfilter' && isset($_POST['id']) && Users::$Session->HasPermission(CMS::$Config['manage']['filter']))
    {
        if (CMS::$MySql->query('DELETE FROM cms_filter WHERE id = :id', array('id' => $_POST['id']))) {
            $Data['valid'] = true;
        } else {
            $Data['valid'] = false;
        }

        exit(json_encode($Data));
    }

    if ($_POST['action'] === 'addValueItem' && isset($_POST['displayname'],$_POST['itemname'],$_POST['price'],$_POST['id']) && Users::$Session->HasPermission(CMS::$Config['manage']['values']))
    {
        if (!$ID = CMS::$MySql->single('SELECT id FROM cms_values_categories WHERE id = :id ', array('id' => $_POST['id'])))
            return;

        if (strlen($_POST['displayname']) < 5 || strlen($_POST['displayname']) > 25) {
            $Data['message'] = CMS::$Lang['valueiteminvaliddisplayname'];
        }
        else if (strlen($_POST['itemname']) === 0 || strlen($_POST['itemname']) > 50) {
            $Data['message'] = CMS::$Lang['valueiteminvaliditemname'];
        }
        else if (($Price = (int)$_POST['price']) <= 0 || $Price > 99999) {
            $Data['message'] = CMS::$Lang['valueiteminvalidprice'];
        }
        else if (!CMS::$MySql->single('SELECT id FROM items_base WHERE item_name = :name', array('name' => $_POST['itemname'])))
        {
            $Data['message'] = CMS::$Lang['valueitemnotexists'];
        }
        else
        {
	    CMS::$MySql->query('INSERT INTO cms_values (category_id,display_name,item_name,price,order_num) SELECT :id,:displayname,:itemname,:price, 1 + IFNULL(MAX(order_num),0) FROM cms_values WHERE category_id = :id2', array('id' => $ID, 'displayname' => htmlspecialchars($_POST['displayname']), 'itemname' => str_replace('*', '_', $_POST['itemname']), 'price' => $Price, 'id2' => $ID));
            $Data['id'] = CMS::$MySql->lastInsertId();
            $Data['message'] = CMS::$Lang['valueitemadded'];
            $Data['valid'] = true;
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'updateValueItem' && isset($_POST['from'],$_POST['to'],$_POST['category_id']) && ($From = (int)$_POST['from']) >= 0 && $From < 10000 && ($To = (int)$_POST['to']) >= 0 && $To < 10000 && Users::$Session->HasPermission(CMS::$Config['manage']['values']))
    {
        if ($From === $To)
            return;

        $Row = CMS::$MySql->row('SELECT GROUP_CONCAT(order_num) AS nums, id FROM((SELECT order_num,id FROM cms_values WHERE category_id = :id ORDER BY order_num LIMIT 1 OFFSET :from) UNION (SELECT order_num,"" FROM cms_values WHERE category_id = :id2 ORDER BY order_num LIMIT 1 OFFSET :to)) AS cms_values', array('from' => $From, 'to' => $To, 'id' => $_POST['category_id'], 'id2' => $_POST['category_id']));

        if (strpos($Row['nums'], ',') !== false)
        {
            $Nums = explode(',', $Row['nums']);

            if ($From < $To) {
                CMS::$MySql->query('UPDATE cms_values SET order_num=order_num-1 WHERE category_id = :id AND order_num > :from AND order_num <= :to', array('id' => $_POST['category_id'], 'from' => $Nums[0], 'to' => $Nums[1]));
            }
            else {
                CMS::$MySql->query('UPDATE cms_values SET order_num=order_num+1 WHERE category_id = :id AND order_num < :from AND order_num >= :to', array('id' => $_POST['category_id'], 'from' => $Nums[0], 'to' => $Nums[1]));
            }

            CMS::$MySql->query('UPDATE cms_values SET order_num = :to WHERE category_id = :id AND id = :from', array('id' => $_POST['category_id'], 'to' => $Nums[1], 'from' => $Row['id']));

            $Data['valid'] = true;
        }
        else {
            $Data['valid'] = false;
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'deleteValueItem' && isset($_POST['id'],$_POST['category_id']) && Users::$Session->HasPermission(CMS::$Config['manage']['values']))
    {
        $Data['valid'] = (bool)CMS::$MySql->query('DELETE FROM cms_values WHERE category_id = :cid AND id = :id', array('cid' => $_POST['category_id'], 'id' => $_POST['id']));

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'deleteValuePage' && isset($_POST['id']) && Users::$Session->HasPermission(CMS::$Config['manage']['values']))
    {
        $Data['valid'] = (bool)CMS::$MySql->query('DELETE cms_values_categories,cms_values FROM cms_values_categories LEFT JOIN cms_values ON cms_values.category_id = cms_values_categories.id WHERE cms_values_categories.id = :id', array('id' => $_POST['id']));

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'addValuePage' && isset($_POST['name']) && Users::$Session->HasPermission(CMS::$Config['manage']['values']))
    {
        if (strlen($_POST['name']) >= 5 && strlen($_POST['name']) <= 25)
        {
	    CMS::$MySql->query('INSERT INTO cms_values_categories (name,order_num) SELECT :name, 1 + IFNULL(MAX(order_num),0) FROM cms_values_categories', array('name' => htmlspecialchars($_POST['name'])));
            $Data['id'] = CMS::$MySql->lastInsertId();
            $Data['message'] = CMS::$Lang['valuepageadded'];
            $Data['valid'] = true;
        }
        else {
            $Data['message'] = CMS::$Lang['valuesinvalidname'];
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'updateValuePage' && isset($_POST['id'],$_POST['name']) && Users::$Session->HasPermission(CMS::$Config['manage']['values']))
    {
        if (strlen($_POST['name']) >= 5 && strlen($_POST['name']) <= 25)
        {
            $Data['valid'] = (bool)CMS::$MySql->query('UPDATE cms_values_categories SET name = :title WHERE id = :id', array('title' => $_POST['name'], 'id' => $_POST['id']));
        }
        else {
            $Data['valid'] = false;
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'viewValuePage' && isset($_POST['id']) && Users::$Session->HasPermission(CMS::$Config['manage']['values']))
    {
        $Data['values'] = CMS::$MySql->query('SELECT id,display_name,item_name,price FROM cms_values WHERE category_id = :id ORDER BY order_num,price ASC', array('id' => $_POST['id']));

        if (!$Data['values']) {
            $Data['message'] = CMS::$Lang['categorynofurni'];
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'updateValuePage' && isset($_POST['from'],$_POST['to']) && ($From = (int)$_POST['from']) >= 0 && $From < 10000 && ($To = (int)$_POST['to']) >= 0 && $To < 10000 && Users::$Session->HasPermission(CMS::$Config['manage']['values']))
    {
        if ($From === $To)
            return;

        $Row = CMS::$MySql->row('SELECT GROUP_CONCAT(order_num) AS nums, id FROM((SELECT order_num,id FROM cms_values_categories ORDER BY order_num LIMIT 1 OFFSET :from) UNION (SELECT order_num,"" FROM cms_values_categories ORDER BY order_num LIMIT 1 OFFSET :to)) AS cms_values_categories', array('from' => $From, 'to' => $To));

        if (strpos($Row['nums'], ',') !== false)
        {
            $Nums = explode(',', $Row['nums']);

            if ($From < $To) {
                CMS::$MySql->query('UPDATE cms_values_categories SET order_num=order_num-1 WHERE order_num > :from AND order_num <= :to', array('from' => $Nums[0], 'to' => $Nums[1]));
            }
            else {
                CMS::$MySql->query('UPDATE cms_values_categories SET order_num=order_num+1 WHERE order_num < :from AND order_num >= :to', array('from' => $Nums[0], 'to' => $Nums[1]));
            }

            CMS::$MySql->query('UPDATE cms_values_categories SET order_num = :to WHERE id = :from', array('to' => $Nums[1], 'from' => $Row['id']));

            $Data['valid'] = true;
        }
        else {
            $Data['valid'] = false;
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'loadBans' && isset($_POST['load']) && ($Load = (int)$_POST['load']) >= 0 && $Load < 10000 && Users::$Session->HasPermission(CMS::$Config['manage']['ban']))
    {
        $Data = CMS::$MySql->query('SELECT bans.id,bans.user_id AS userid,u1.username,u2.username AS staff_username,ban_expire,type,ban_reason FROM bans LEFT JOIN users u1 ON u1.id = bans.user_id LEFT JOIN users u2 ON u2.id = bans.user_staff_id WHERE u1.rank < :rank ORDER BY bans.id DESC LIMIT ' . $Load * 25 . ',25', array('rank' => Users::$Session->Data['rank']));

        $Count = count($Data);
        for ($i = 0; $i < $Count; $i++)
        {
            $Data[$i]['ban_expire'] = date('d-m-Y H:i:s', $Data[$i]['ban_expire']);
            $Data[$i]['ban_reason'] = $Data[$i]['ban_reason'] ? htmlspecialchars($Data[$i]['ban_reason']) : CMS::$Lang['defaultbanreason'];
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'searchBans' && isset($_POST['username'],$_POST['ip'],$_POST['strict']) && Users::$Session->HasPermission(CMS::$Config['manage']['ban']))
    {
        if (empty($_POST['username']) && empty($_POST['ip'])) {
            $Data['message'] = CMS::$Lang['missingiporusername'];
        }
        else if ($Data['bans'] = CMS::$MySql->query('SELECT bans.id,bans.user_id AS userid,u1.username,u2.username AS staff_username,ban_expire,type,ban_reason FROM bans LEFT JOIN users u1 ON u1.id = bans.user_id LEFT JOIN users u2 ON u2.id = bans.user_staff_id WHERE u1.rank < :rank AND '.(($_POST['username']) ? (($_POST['strict'] == '0') ? 'u1.username LIKE :data' : 'u1.username = :data') : (($_POST['strict'] == '0') ? 'u1.ip_current LIKE :data' : 'u1.ip_current = :data')).' ORDER BY bans.id DESC LIMIT 25', array('rank' => Users::$Session->Data['rank'], 'data' => ($_POST['username']) ? (($_POST['strict'] == '0') ? $_POST['username'].'%' : $_POST['username']) : (($_POST['strict'] == '0') ? $_POST['ip'].'%' : $_POST['ip']))))
        {
            $Count = count($Data['bans']);
            for ($i = 0; $i < $Count; $i++) {
                $Data['bans'][$i]['ban_expire'] = date('d-m-Y H:i:s', $Data['bans'][$i]['ban_expire']);
            }
        }
        else {
            $Data['message'] = CMS::$Lang['noplayersfound'];
        }
        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'deleteBan' && isset($_POST['id']) && Users::$Session->HasPermission(CMS::$Config['manage']['ban']))
    {
        $Data['valid'] = (bool)CMS::$MySql->query('DELETE bans.* FROM bans LEFT JOIN users ON users.id = bans.user_id WHERE rank < :rank AND bans.id = :id', array('rank' => Users::$Session->Data['rank'], 'id' => $_POST['id']));

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'banPlayer' && isset($_POST['username'],$_POST['expire'],$_POST['reason'],$_POST['type']) && Users::$Session->HasPermission(CMS::$Config['manage']['ban']))
    {
        if (!isset(($Type = Array (1 => 'account', 2 => 'ip', 3 => 'machine', 4 => 'super'))[($Number = (int)$_POST['type'])]))
            return;

        $Player = CMS::$MySql->row('SELECT users.id,username,online,ip_current,users.machine_id,type FROM users LEFT JOIN bans ON bans.user_id = users.id AND ban_expire > UNIX_TIMESTAMP() WHERE rank < :rank AND username = :username', array('rank' => Users::$Session->Data['rank'], 'username' => $_POST['username']));

        if (!$Player['id']) {
            $Data['message'] = CMS::$Lang['playernotexistsorforbiddenban'];
        }
        else if ($Player['type']) {
            $Data['message'] = CMS::$Lang['playeralreadybanned'];
        }
        else if (($Expire = time() + (int)$_POST['expire']) < time() + 600 || $Expire > time() + 157784630) {
            $Data['message'] = CMS::$Lang['bantimeinvalid'];
        }
        else if (strlen($_POST['reason']) > 50) {
            $Data['message'] = CMS::$Lang['banreasontoolong'];
        }
        else
        {
            CMS::$MySql->query('INSERT INTO bans (user_id,ip,machine_id,user_staff_id,timestamp,ban_expire,ban_reason,type) VALUES (:userid,:ip,:machine,:staffid,:time,:expire,:reason,:type)', array('userid' => $Player['id'], 'ip' => $Player['ip_current'], 'machine' => $Player['machine_id'], 'staffid' => Users::$Session->ID, 'time' => time(), 'expire' => $Expire, 'reason' => $_POST['reason'], 'type' => $Type[$Number]));
            if ($Player['online'] == 1) {
                Site::RCON('disconnect', array('user_id' => $Player['id']));
            }
            $Data['ban'] = CMS::$MySql->row('SELECT bans.id,u1.username,u2.username AS staff_username,ban_expire,type,ban_reason FROM bans LEFT JOIN users u1 ON u1.id = bans.user_id LEFT JOIN users u2 ON u2.id = bans.user_staff_id WHERE bans.id = :id', array('id' => CMS::$MySql->lastInsertId()));
            $Data['ban']['ban_expire'] = date('d-m-Y H:i:s', $Data['ban']['ban_expire']);
            $Data['ban']['ban_reason'] = $Data['ban']['ban_reason'] ? htmlspecialchars($Data['ban']['ban_reason']) : CMS::$Lang['defaultbanreason'];
            $Data['valid'] = true;
            $Data['message'] = str_ireplace('%username%', $Player['username'], CMS::$Lang['bansuccess']);
        }
        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'editPlayer' && isset($_POST['id'],$_POST['username'],$_POST['email'],$_POST['password'],$_POST['credits'],$_POST['duckets'],$_POST['diamonds'],$_POST['crowns'],$_POST['rank']) && Users::$Session->HasPermission(CMS::$Config['manage']['users']))
    {
        if (!$Player = CMS::$MySql->row('SELECT id,username,mail,password,online FROM users WHERE rank < :rank AND id = :id', array('rank' => Users::$Session->Data['rank'], 'id' => $_POST['id'])))
            return;

        if (!$Rank = CMS::$MySql->single('SELECT id FROM permissions WHERE id < :rank AND id = :id', array('rank' => Users::$Session->Data['rank'], 'id' => $_POST['rank'])))
            return;

        if ($Player['online'] != 0) {
            Site::RCON('disconnect', array('user_id' => $Player['id']));
            $Data['message'] = str_ireplace('%username%', $Player['username'], CMS::$Lang['playereditonline']);
        }
        else if (($Credits = (int)$_POST['credits']) < 0 || ($Duckets = (int)$_POST['duckets']) < 0 || ($Diamonds = (int)$_POST['diamonds']) < 0 || ($Crowns = (int)$_POST['crowns']) < 0) {
            $Data['message'] = CMS::$Lang['invalidcurrency'];
        }
        else if (!Users::ValidName($_POST['username'])) {
            $Data['message'] = CMS::$Lang['invalidname'];
        }
        else if ($Player['username'] != $_POST['username'] && !Users::NameFree($_POST['username'])) {
            $Data['message'] = CMS::$Lang['nameinuse'];
        }
        else if (!Users::ValidMail($_POST['email'])) {
            $Data['message'] = CMS::$Lang['invalidmail'];
        }
        else if ($Player['mail'] != $_POST['email'] && !Users::MailFree($_POST['email'])) {
            $Data['message'] = CMS::$Lang['mailinuse'];
        }
        else if ($_POST['password'] && !Users::ValidPass($_POST['password'])) {
            $Data['message'] = CMS::$Lang['invalidpass'];
        }
        else {
            CMS::$MySql->query("UPDATE users SET username=:username,mail=:email,password=:password,credits=:credits,rank=:rank WHERE id = :id", array('username' => $_POST['username'], 'email' => $_POST['email'], 'password' => $_POST['password'] ? Site::Hash($_POST['password']) : $Player['password'], 'credits' => $Credits, 'rank' => $Rank, 'id' => $Player['id']));
            CMS::$MySql->query("INSERT INTO users_currency (user_id, type, amount) VALUES(:userid, 0, :amount),(:userid2, 5, :amount2),(:userid3, 103, :amount3) ON DUPLICATE KEY UPDATE amount = VALUES(amount)", array('userid' => $Player['id'], 'userid2' => $Player['id'], 'userid3' => $Player['id'], 'amount' => $Duckets, 'amount2' => $Diamonds, 'amount3' => $Crowns));
            $Data['valid'] = true;
            $Data['message'] = CMS::$Lang['playereditsuccess'];
        }
        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'getPlayerData' && isset($_POST['id']) && Users::$Session->HasPermission(CMS::$Config['manage']['users']))
    {
        if (!($Data['player'] = CMS::$MySql->row('SELECT users.id,username,mail,credits,rank,IFNULL(a1.amount,0) AS duckets,IFNULL(a2.amount,0) AS diamonds,IFNULL(a3.amount,0) AS crowns,a4.type AS ban_type FROM users LEFT JOIN users_currency a1 ON a1.user_id = users.id AND a1.type = 0 LEFT JOIN users_currency a2 ON a2.user_id = users.id AND a2.type = 5 LEFT JOIN users_currency a3 ON a3.user_id = users.id AND a3.type = 103 LEFT JOIN bans a4 ON a4.user_id = users.id AND a4.ban_expire > UNIX_TIMESTAMP() WHERE rank < :rank AND users.id = :id', array('rank' => Users::$Session->Data['rank'], 'id' => $_POST['id'])))['id'])
            return;

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'loginWithPlayer' && isset($_POST['id']) && Users::$Session->HasPermission(CMS::$Config['manage']['users']))
    {
        if (!$Player = CMS::$MySql->row('SELECT id,ip_current FROM users WHERE rank < :rank AND id = :id', array('rank' => Users::$Session->Data['rank'], 'id' => $_POST['id'])))
            return;

        Users::CookieGenerator($Player['id'], $Player['ip_current']);
        $Data['valid'] = true;

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'searchPlayers' && isset($_POST['username'],$_POST['ip'],$_POST['strict']) && Users::$Session->HasPermission(CMS::$Config['manage']['users']))
    {
        if (empty($_POST['username']) && empty($_POST['ip'])) {
            $Data['message'] = CMS::$Lang['missingiporusername'];
        }
        else if ($Data['players'] = CMS::$MySql->query('SELECT id,username,mail,last_online,ip_current FROM users WHERE rank < :rank AND '.(($_POST['username']) ? (($_POST['strict'] == '0') ? 'username LIKE :data' : 'username = :data') : (($_POST['strict'] == '0') ? 'ip_current LIKE :data' : 'ip_current = :data')).' ORDER BY id DESC LIMIT 25', array('rank' => Users::$Session->Data['rank'], 'data' => ($_POST['username']) ? (($_POST['strict'] == '0') ? $_POST['username'].'%' : $_POST['username']) : (($_POST['strict'] == '0') ? $_POST['ip'].'%' : $_POST['ip']))))
        {
            $Count = count($Data['players']);
            for ($i = 0; $i < $Count; $i++) {
                $Data['players'][$i]['last_online'] = date('d-m-Y H:i:s', $Data['players'][$i]['last_online']);
            }
        }
        else {
            $Data['message'] = CMS::$Lang['noplayersfound'];
        }
        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'loadWordList' && isset($_POST['load']) && ($Load = (int)$_POST['load']) >= 0 && $Load < 10000 && Users::$Session->HasPermission(CMS::$Config['manage']['wordfilter']))
    {
        $Data = CMS::$MySql->query('SELECT wordfilter.id,`key`,replacement,hide,username FROM wordfilter LEFT JOIN users ON users.id = added_by ORDER by wordfilter.id DESC LIMIT ' . $Load * 25 . ',25');

        $Count = count($Data);
        for ($i = 0; $i < $Count; $i++)
        {
            $Data[$i]['key'] = htmlspecialchars($Data[$i]['key']);
            $Data[$i]['replacement'] = htmlspecialchars($Data[$i]['replacement']);
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'deleteWord' && isset($_POST['id']) && Users::$Session->HasPermission(CMS::$Config['manage']['wordfilter']))
    {
        $Data['valid'] = (bool)CMS::$MySql->query('DELETE FROM wordfilter WHERE id = :id', array('id' => $_POST['id']));

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'addWord' && isset($_POST['word'], $_POST['replacement'], $_POST['hide']) && Users::$Session->HasPermission(CMS::$Config['manage']['wordfilter']))
    {
        if (strlen($_POST['word']) < 3 || strlen($_POST['word']) > 25 || strlen($_POST['replacement']) < 3 || strlen($_POST['replacement']) > 25)
        {
            $Data['message'] = CMS::$Lang['wordfilterwordinvalidlength'];
        }
        else if (CMS::$MySql->single('SELECT COUNT(*) FROM wordfilter WHERE `key` = :word', array('word' => $_POST['word'])) >= 1)
        {
            $Data['message'] = CMS::$Lang['wordfilterwordalreadyadded'];
        }
        else
        {
            CMS::$MySql->query('INSERT INTO wordfilter (`key`,`replacement`,`hide`,`report`,`added_by`) VALUES (:word,:replacement,:hide,:report,:userid)', array('word' => $_POST['word'], 'replacement' => $_POST['replacement'], 'hide' => $_POST['hide'] == '1' ? '1' : '0', 'report' => $_POST['hide'] == '1' ? '1' : '0', 'userid' => Users::$Session->ID));

            $Data['valid'] = true;
            $Data['word'] = CMS::$MySql->row('SELECT wordfilter.id,`key`,replacement,hide,username FROM wordfilter LEFT JOIN users on users.id = added_by WHERE wordfilter.id = :id', array('id' => CMS::$MySql->lastInsertId()));
            $Data['message'] = CMS::$Lang['wordfilterwordadded'];

            Site::RCON('updatewordfilter', array('addedBy' => Users::$Session->Data['username'], 'addedWord' => $_POST['word']));

        }
        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'loadNewsList'  && isset($_POST['load']) && ($Load = (int)$_POST['load']) >= 0 && $Load < 10000 && Users::$Session->HasPermission(CMS::$Config['manage']['news']))
    {
        $Data = CMS::$MySql->query('SELECT cms_news.id,title,shortstory,published,username FROM cms_news LEFT JOIN users ON users.id = author ORDER BY cms_news.id DESC LIMIT ' . $Load * 25 . ',25');

        $Count = count($Data);
        for ($i = 0; $i < $Count; $i++)
        {
            $Data[$i]['published'] = date('d-m-Y', $Data[$i]['published']);
        }

        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'getNews' && isset($_POST['id']) && Users::$Session->HasPermission(CMS::$Config['manage']['news']))
    {
        if (CMS::$MySql->single('SELECT id FROM cms_news WHERE id = :newsid', array('newsid' => $_POST['id'])))
        {
            $Data['news'] = CMS::$MySql->row('SELECT id,title,shortstory,longstory,image,comments FROM cms_news WHERE id = :newsid', array('newsid' => $_POST['id']));
            $Data['valid'] = true;
        }
        else {
            $Data['valid'] = false;
        }
        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'deleteNews' && isset($_POST['id']) && Users::$Session->HasPermission(CMS::$Config['manage']['news']))
    {
        $Data['valid'] = (bool)CMS::$MySql->query('DELETE FROM cms_news WHERE id = :id', array('id' => $_POST['id']));
        if ($Data['valid']) {
            CMS::$Cache->delete('lastnews');
            CMS::$Cache->delete('newslist');
        }
        exit (json_encode($Data));
    }

    if ($_POST['action'] === 'addNews' && isset($_POST['title'], $_POST['shortstory'], $_POST['comments'], $_POST['image'], $_POST['longstory']) && Users::$Session->HasPermission(CMS::$Config['manage']['news']))
    {
        if (strlen($_POST['title']) < 5 || strlen($_POST['title']) > 30)
        {
            $Data['message'] = CMS::$Lang['newstitlelength'];
        }
        else if (strlen($_POST['shortstory']) < 5 || strlen($_POST['shortstory']) > 100)
        {
            $Data['message'] = CMS::$Lang['shortstorylength'];
        }
        else
        {
            require_once 'Simple/Includes/Modules/autoload.php';
            $Slugify = new Cocur\Slugify\Slugify();

            if (isset($_POST['update']))
            {
                if (!CMS::$MySql->single('SELECT id FROM cms_news WHERE id = :newsid', array('newsid' => $_POST['update'])))
                    return;

                CMS::$MySql->query('UPDATE cms_news SET slug=:slug,title=:title,shortstory=:shortstory,longstory=:longstory,image=:image,comments=:comments WHERE id = :newsid', array('slug' => $Slugify->slugify($_POST['title']), 'title' => htmlspecialchars($_POST['title']), 'shortstory' => htmlspecialchars($_POST['shortstory']), 'longstory' => $_POST['longstory'], 'image' => $_POST['image'], 'comments' => $_POST['comments'] == '1' ? '1' : '0', 'newsid' => $_POST['update']));
                $Data['message'] = CMS::$Lang['newsupdated'];
            }
            else
            {
                CMS::$MySql->query('INSERT INTO cms_news (slug,title,shortstory,longstory,published,image,author,comments) VALUES (:slug,:title,:shortstory,:longstory,:published,:image,:author,:comments)', array('slug' => $Slugify->slugify($_POST['title']), 'title' => htmlspecialchars($_POST['title']), 'shortstory' => htmlspecialchars($_POST['shortstory']), 'longstory' => $_POST['longstory'], 'published' => time(), 'image' => $_POST['image'], 'author' => Users::$Session->ID, 'comments' => $_POST['comments'] == '1' ? '1' : '0'));
                $Data['news'] = CMS::$MySql->row('SELECT cms_news.id,title,shortstory,longstory,image,published,username FROM cms_news LEFT JOIN users ON users.id = author WHERE cms_news.id = :newsid', array('newsid' => CMS::$MySql->lastInsertId()));
                $Data['news']['published'] = date('d-m-Y', $Data['news']['published']);
                $Data['message'] = CMS::$Lang['newsplaced'];

                //Site::RCON('ImageHotelAlert', array('url' => CMS::$Config['cms']['url'].'/news/'.$Data['news']['id'], 'url_message' => 'Ga naar het nieuwsartikel', 'title' => $_POST['title'], 'message' => $_POST['shortstory']));
            }

            CMS::$Cache->delete('lastnews');
            CMS::$Cache->delete('newslist');

            $Data['valid'] = true;
        }

        exit (json_encode($Data));
    }
}
