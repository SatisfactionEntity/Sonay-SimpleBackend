<?php
$this->WriteInc('Header');
?>
    <link rel="stylesheet" href="/Simple/Assets/css/home/style.css">
    <link rel="stylesheet" href="/Simple/Assets/css/Sonay/style.css?<?= time() ?>">
<link type="text/css" href="/Simple/Assets/css/Sonay/profile.css?v=9" rel="stylesheet">

    <div class="row">
        <?php
        $Url = strtolower(isset(CMS::$Router->Request->SubUrls[0]) ? substr(CMS::$Router->Request->SubUrls[0], 1) : (Users::$Session == true ? Users::$Session->Data['username'] : ''));
        if (!$Player = CMS::$Cache->get('player-'.md5($Url)))
        {
            if (($Player = CMS::$MySql->row('SELECT users.id,users.username,users.motto,users.look,users.credits,users.home, IFNULL(a1.amount,0) AS duckets, IFNULL(a2.amount,0) AS diamonds, IFNULL(a3.amount,0) AS crowns, COUNT(a4.id) AS pets, IFNULL(a5.respects_received,0) AS respects_received, IFNULL(a5.respects_given,0) AS respects_given, IFNULL(a5.achievement_score,0) AS achievement_score FROM users LEFT JOIN users_currency a1 ON a1.user_id = users.id AND a1.type = 0 LEFT JOIN users_currency a2 ON a2.user_id = users.id AND a2.type = 5 LEFT JOIN users_currency a3 ON a3.user_id = users.id AND a3.type = 103 LEFT JOIN users_pets a4 ON a4.user_id = users.id LEFT JOIN users_settings a5 ON a5.user_id = users.id WHERE users.username = :username', array('username' => $Url)))['id'])
            {
                $Player['rooms'] = CMS::$MySql->query('SELECT id,name FROM rooms WHERE owner_id = :userid', array('userid' => $Player['id']));
                $Player['badges'] = CMS::$MySql->query('SELECT badge_code FROM users_badges WHERE user_id = :userid', array('userid' => $Player['id']));
                CMS::$Cache->set('player-'.md5($Url), $Player, 30);
            }
        }

        if (!isset($Player['id'])) {
            echo '<div class="col-sm-7"><div class="box"><h2 class="red">' . CMS::$Lang['homenotfound'] . '</h2><div class="inner">' . CMS::$Lang['homenotfound2'] . '</div></div></div>';
        } else if (!$Player['home'] && Users::$Session == false || !$Player['home'] && Users::$Session->ID != $Player['id']) {
            echo '<div class="col-sm-7"><div class="box"><h2 class="red">' . CMS::$Lang['homedisabled'] . '</h2><div class="inner">' . CMS::$Lang['homedisabled2'] . '</div></div></div>';
        } else {
            echo '<div class="col-sm-8"><div class="box"><h2 class="green">' . CMS::$Lang['homeinfo'] . '</h2><div class="inner">' . str_ireplace(array('%motto%','%credits%','%duckets%','%diamonds%','%pets%','%respectgot%','%respectgiven%','%score%'), array(htmlspecialchars($Player['motto']),$Player['credits'],$Player['duckets'],$Player['diamonds'],$Player['pets'],$Player['respects_received'],$Player['respects_given'],$Player['achievement_score']), CMS::$Lang['homestats']) . '</div></div><div class="box">';
            if ($Player['rooms']) {
                echo '<h2 class="red">' . str_ireplace("%amount%", count($Player['rooms']), CMS::$Lang['homeroomsamount']) . '</h2><div class="inner"><table border="0" cellspacing="1" style="width:100%" class="list_table"><tbody>';
                foreach ($Player['rooms'] as $Row) {
                    echo '<tr onclick="enterroom(' . $Row['id'] . ');"><td>' . htmlspecialchars($Row['name']) . '</tr></td>';
                }
                echo '</table></tbody>';
            } else {
                echo '<h2 class="red">' . CMS::$Lang['homerooms'] . '</h2><div class="inner">' . CMS::$Lang['homenorooms'];
            }
            echo '</div></div></div><div class="col-sm-4"><div class="box"><h2 class="orange">' . $Player['username'] . '</h2><div class="inner"><div style="width:128px;height:120px;float:right;background:url('.CMS::$Config['cms']['avatarlocation'].'?figure=' . $Player['look'] . '&action=wav&gesture=sml&head_direction=3&direction=3&crr=3&size=l) no-repeat scroll 0px -40px transparent; margin: -30px -20px 0 0;"></div>' . str_ireplace(array('%username%', '%target%', '%id%', '%hotelname%'), array(Users::$Session == true ? Users::$Session->Data['username'] : CMS::$Lang['guest'], $Player['username'], number_format($Player['id'], 0, ',', '.'), CMS::$Config['cms']['hotelname']), Users::$Session == true && $Player['id'] == Users::$Session->ID ? CMS::$Lang['homeaboutme'] : CMS::$Lang['homeabout']) . '</div></div><div class="box">';
            if ($Player['badges']) {
                echo '<h2 class="orange">' . str_ireplace("%amount%", count($Player['badges']), CMS::$Lang['homebadgesamount']) . '</h2><div class="container" style="top:10px;">';
                foreach ($Player['badges'] as $Row) {
                    echo '<div class="col-sm-2" style="height:50px;background:url(' . CMS::$Config['cms']['badgemap'] . '/' . $Row['badge_code'] . '.gif) no-repeat"></div>';
                }
            } else {
                echo '<h2 class="red">' . CMS::$Lang['homebadges'] . '</h2><div class="inner">' . CMS::$Lang['homenobadges'];
            }
            echo '</div></div></div>';
        }
        ?>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
<?php
$this->WriteInc('Footer');
?>