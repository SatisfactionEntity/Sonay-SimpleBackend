<?php
$this->WriteInc('Header');
?>
    <style>
        .loading {
            width: 50%;
            float: none !important;
            margin: 10px 0 10px 0;
            position: relative !important;
            INNER: 0 !important;
            top: 0 !important;
        }
        .top_item {
            text-align: center;
            top: 3px;
        }
    </style>
    <div class="row">
        <?php

        if (!$Query = CMS::$Cache->get('stats')) {
            $Query['online'] = CMS::$MySql->query("SELECT username,look,round(sum(online_time / 3600), 0) AS data FROM users_settings INNER JOIN users ON users.rank <= 11 AND users.id = user_id GROUP BY users.id ORDER BY data DESC LIMIT 5");
            $Query['credits'] = CMS::$MySql->query("SELECT username,look,credits AS data FROM users ORDER BY data DESC LIMIT 5");
            $Query['achievement'] = CMS::$MySql->query("SELECT username,look,achievement_score AS data FROM users_settings INNER JOIN users ON users.rank <= 11 AND users.id = user_id ORDER BY data DESC LIMIT 5");
            $Query['duckets'] = CMS::$MySql->query("SELECT username,look,amount AS data FROM users_currency INNER JOIN users ON users.rank <= 11 AND users.id = user_id WHERE type = '0' ORDER BY data DESC LIMIT 5");
            $Query['roomvisits'] = CMS::$MySql->query("SELECT username,look,progress AS data FROM users_achievements INNER JOIN users ON users.rank <= 11 AND  users.id = id WHERE `achievement_name` = 'RoomEntry' ORDER BY data DESC LIMIT 5");
            $Query['diamonds'] = CMS::$MySql->query("SELECT username,look,amount AS data FROM users_currency INNER JOIN users ON users.rank <= 11 AND users.id = user_id WHERE type = '5' ORDER BY data DESC LIMIT 5");
            $Query['respectreceived'] = CMS::$MySql->query("SELECT username,look,respects_received AS data FROM users_settings INNER JOIN users ON users.rank <= 11 AND users.id = user_id ORDER BY data DESC LIMIT 5");
            $Query['friends'] = CMS::$MySql->query("SELECT users.username,users.look,COUNT(user_one_id) AS data FROM messenger_friendships INNER JOIN users ON users.rank <= 11 AND users.id = messenger_friendships.user_one_id GROUP BY messenger_friendships.user_one_id ORDER BY data DESC LIMIT 5");
            CMS::$Cache->set('stats', $Query, 86400);
        }

        $i = 0;
        foreach ($Query as $Key => $Data)
        {
            if ($i %2 == 0)
            {
                echo '<div class="col-sm-3 most">';
            }

            echo '<div class="box"><h2 class="'.($i == 1 || $i == 2 || $i == 5 || $i == 6 ? 'red' : 'blue').'">'.CMS::$Lang['stats'.$Key].'</h2><div class="top_item"><img class="loading" src="/Simple/Assets/img/loading.gif"><div class="stats" style="display:none">';

            if ($Data) {
                foreach ($Data as $Row) {
                    echo '<li><img src="'.CMS::$Config['cms']['avatarlocation'].'?figure=' . $Row['look'] . '&head_direction=2&gesture=sml&headonly=1"><a href="/home/' . $Row['username'] . '">' . $Row['username'] . '</a><p>' . number_format($Row['data'], 0, ',', '.') . CMS::$Lang['stats' . $Key . 'type'] . '</p></li>';
                }
            }
            else
            {
                echo '<div class="inner">Geen spelers gevonden.</div>';
            }

            echo '<div style="clear:both"></div></div></div></div>';

            if ($i %2 == 1)
            {
                echo '</div>';
            }

            $i++;
        }
        ?>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/top.js"></script>
<?php
$this->WriteInc('Footer');
?>