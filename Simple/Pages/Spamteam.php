<?php
$this->WriteInc('Header');
?>
<div class="row">
    <div class="col-sm-4">
        <div class="box">
            <h2 class="green">De spamteam managers</h2>
            <div class="inner">
                <p>De Spamteam managers zijn de leiders van het Spamteam. Samen met de Hoofdspammers zullen zij
                    Spamacties, Spamcompetities en prijzen voor de Spammers verzorgen. Ook zullen zij ervoor zorgen dat
                    er geen inactieve leden in het Spamteam zitten en dat de Spamacties soepel verlopen.<br>
					<?= CMS::$Config['cms']['hotelname'] ?> spamteam zoekt nog leden solliciteer snel voor 1 van deze functies.</p>
            </div>
        </div>
        <div class="box">
            <h2 class="blue">De spamteam leden</h2>
            <div class="inner">
                <img src="<?= CMS::$Config['cms']['badgemap'] ?>/SPAM.gif" align="right">
                <p>
                    De Spamteam leden zijn de basis van het Spamteam. Zonder hun waren er geen Spamacties &
                    Spamcompetities, het team zet zich wekelijks in om <?= CMS::$Config['cms']['hotelname'] ?> aan de top te houden. In samenwerking
                    met de Hoofdspammers, zullen zij elke dag spelers naar <?= CMS::$Config['cms']['hotelname'] ?> halen.
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-8">
        <?php
        $Data = '';
        $Players = CMS::$MySql->query('SELECT username,look,last_online,motto,online,rank FROM users WHERE rank in (18,16,14) ORDER BY rank DESC');
        $Ranks = CMS::$MySql->query('SELECT id,rank_name,color FROM permissions WHERE id in (18,16) ORDER BY id DESC');
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
