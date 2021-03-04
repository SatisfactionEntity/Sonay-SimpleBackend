<?php
$this->WriteInc('Header');
?>
<div class="row">
    <div class="col-sm-4">
        <div class="box">
            <h2 class="blue">Bouwteam</h2>
            <div class="inner">
                <p>Het <?= CMS::$Config['cms']['hotelname'] ?> bouwteam zorgt voor de leukste & mooiste kamers, de mooiste bouwwerken en de gekste dingen
                    binnen het hotel! De leden zullen door een enkeling worden begeleid, die samen het management
                    vormen. <?= CMS::$Config['cms']['hotelname'] ?> bouwteam zoekt nog leden solliciteer snel voor 1 van deze functies.</p>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <?php
        $Data = '';
        $Players = CMS::$MySql->query('SELECT username,look,last_online,motto,online,rank FROM users WHERE rank in (10,8) ORDER BY rank DESC');
        $Ranks = CMS::$MySql->query('SELECT id,rank_name,color FROM permissions WHERE id in (10,8) ORDER BY id DESC');
        if (is_array($Ranks))
        {
            foreach ($Ranks as $Row) {
                $found = false;
                $Data .= '<div class="box"><h2 class="' . $Row['color'] . '">' . $Row['rank_name'] . '</h2>';
                if (is_array($Players)) {
                    foreach ($Players as $Player) {
                        if ($Player['rank'] == $Row['id']) {
                            $found = true;
                            $Data .= '<div class="staffBox"><img style="margin-bottom:4px;margin-left:4px;position:absolute" src="'.CMS::$Config['cms']['avatarlocation'].'?figure=' . $Player['look'] . '&head_direction=3&headonly=true&gesture=sml"><span class="status"><img src="/Simple/Assets/img/' . ($Player['online'] == "1" ? 'online' : 'offline') . '.gif"></span><table style="margin-left:60px;padding-top:6px"><tr style="padding:0"><td><strong><a href="/home/' . $Player['username'] . '">' . $Player['username'] . '</a></strong><br>' . htmlspecialchars($Player['motto']) . '<br>Laatst online: ' . date('d-m-Y H:i:s', $Player['last_online']) . '</td></tr></table></div>';
                        }
                    }
                }
                if (!$found) {
                    $Data .= '<div class="inner">'.str_ireplace("%rank%", $Row['rank_name'], CMS::$Lang['ranknotfound']).'</div>';
                }
                $Data .= '</div>';
            }
        }
        else
        {
            $Data .= '<div class="col-sm-7"><div class="box"><h2 class="red">'.CMS::$Lang['noranksfound'].'</h2><div class="inner">'.CMS::$Lang['noranksfound2'].'</div></div></div>';
        }
        echo $Data;
        ?>
    </div>
</div>
<script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
<?php
$this->WriteInc('Footer');
?>
