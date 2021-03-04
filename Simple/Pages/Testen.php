<?php
$this->WriteInc('Header');
?>
<?php
$Referral = CMS::$MySql->row('SELECT COUNT(*) AS invited, (SELECT level FROM cms_referral_rewards WHERE friends_needed <= COUNT(*) ORDER BY level DESC LIMIT 1) AS level FROM cms_referral WHERE target_id = :userid', array('userid' => Users::$Session->ID));
?>
<link rel="stylesheet" href="/Simple/Assets/css/Sonay/style.css?<?= time() ?>">
    <link rel="stylesheet" href="/Simple/Assets/css/profile/pure.css">
    <div class="container">
        <div class="row">
			<div class="col-sm-6">
			<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#00796B;background-image:url(/Simple/Assets/img/web_promo_2.png);background-repeat: no-repeat;background-position: right;background-size: cover;height: 80px;">
						<div class="title2"><font color="white">Informatie Over Je Zelf</font></div>
						<div class="desc2"><font color="white">Referral informatie</font></div>
					</div>
					<div class="png20 stataantal">
						<div class="list-group-item">
                        <img src="/Simple/Assets/img/link.gif"> Mijn link:
                        <span style="float: right;margin-top: 11px;"><a href="#" id="openModal"><?= CMS::$Config['cms']['url'] ?>/r/<?= Users::$Session->ID ?></a></span>
                    </div>
                    <div class="list-group-item">
                        <img src="/Simple/Assets/img/level.gif"> Level:
                        <span style="float:right;margin-top:11px">Level <?= (int)$Referral['level'] ?></span>
                    </div>
                    <div class="list-group-item">
                        <img src="/Simple/Assets/img/friends.png"> Vrienden:
                        <span style="float: right;margin-top: 11px;">
                        <?= '<span id="invited">' . $Referral['invited'] . '</span> ' . ($Referral['invited'] == 1 ? CMS::$Lang['friend'] : CMS::$Lang['friends']); ?>
                        </span>
                    </div>
					</div>
				</div>
				<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#00796B;background-image:url(/simple/Assets/img/djbox.png);background-repeat: no-repeat;background-position: right;background-size: cover;height: 80px;">
						<div class="title2"><font color="white">Level Reward Systeem</font></div>
						<div class="desc2"><font color="white">Mooi he? 'N functie voor beloningen?</font></div>
					</div>
					<div class="png20 stataantal">
            <div class="box">
                <?php
                if (!$Rewards = CMS::$Cache->get('referralrewards')) {
                    $Rewards = CMS::$MySql->query('SELECT level,reward_icon,title FROM cms_referral_rewards');
                    CMS::$Cache->set('referralrewards', $Rewards ? $Rewards : $Rewards = 'none', 86400);
                }
                if (is_array($Rewards)) {
                    foreach ($Rewards as $Row) {
                        echo '<div class="list-group-item"><img src="/Simple/Assets/img/'.$Row['reward_icon'].'"> '.$Row['title'].'<span style="float:right;margin-top:11px">Level '.$Row['level'].'</span></div>';
                    }
                } ?>
            </div>
					</div>
				</div>
			</div>
			<div class="col-6">
				<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#00796B;background-image:url(/Simple/Assets/img/WinnersPodium_lge.png);background-repeat: no-repeat;background-position: right;background-size: cover;height: 80px;">
						<div class="title2"><font color="white">Uitleg van Referral</font></div>
						<div class="desc2"><font color="white">Hoe werkt referral systeem precies?</font></div>
					</div>
					<div class="png20 stataantal">
                <?= CMS::$Config['cms']['hotelname'] ?> is dankbaar wanneer jij een andere gebruiker <?= CMS::$Config['cms']['hotelname'] ?> laat spelen. Daarom willen we je graag daarvoor belonen.
                    <br><br>
                    Je kunt hiernaast een speciale referral link vinden.
                    Stuur deze link naar anderen en nadat ze zich geregistreerd hebben bij <?= CMS::$Config['cms']['hotelname'] ?> krijg je punten. Hoe meer mensen je verwijst, hoe beter je cadeau's zullen zijn!
                    <br><br>
                    <p style="text-align: center">
                        <a id="foo" href="#"><?= CMS::$Config['cms']['url'] . '/r/' . Users::$Session->ID ?></a>
                        <input style="display:none" id="foo" type="text" value="<?= CMS::$Config['cms']['url'] . '/r/' . Users::$Session->ID ?>">
                        <button class="btn" data-clipboard-action="copy" data-clipboard-target="#foo">Kopieer
                        </button>
                    </p></div>
				</div>
				
				<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#00796B;background-image:url(/Simple/Assets/img/devbox.png);background-repeat: no-repeat;background-position: right;background-size: cover;height: 80px;">
						<div class="title2"><font color="white">Oplopende Cadeau's</font></div>
						<div class="desc2"><font color="white">Bouw jij je reward pijl omhoog?</font></div>
					</div>
					<div class="png20 stataantal">
                <div class="error"></div>
                    <table id="packages" style="width:100%">
                        <?php
                        if ($Rewards = CMS::$MySql->query('SELECT level,friends_needed,present_icon FROM cms_referral_rewards t1 WHERE NOT EXISTS (SELECT 1 FROM cms_referral_claimed t2 WHERE t2.user_id = :userid AND t1.level = t2.reward_id ) ORDER BY level', array('userid' => Users::$Session->ID)))
                        {
                            $i = 0;
                            foreach ($Rewards as $Row)
                            {
                               echo '<tr data-id="'.$Row['level'].'" ' . ($i > 1 ? ' style="display:none"' : '').'><td class="price"><div class="present"><img src="/Simple/Assets/img/'.$Row['present_icon'].'"</div></td><td class="second"><p class="title">Level '.$Row['level'].'</p><p class="desc">'.($Referral['invited'] >= $Row['friends_needed'] ? CMS::$Lang['invitefriendsenough'] : str_ireplace(array('%amount%','%friends%'), array($Row['friends_needed'] - $Referral['invited'], $Row['friends_needed'] - $Referral['invited'] == 1 ? strtolower(CMS::$Lang['friend']) : strtolower(CMS::$Lang['friends'])), CMS::$Lang['invitefriendsopen'])).'</p></td><td'.($i > 0 ? ' style="display:none"' : '').' class="button"><button data-present="'. $Row['present_icon'] .'" data-id="'.$Row['level'].'" class="show btn '.($Referral['invited'] >= $Row['friends_needed'] ? 'btn-success">'.CMS::$Lang['claim'] : 'btn-danger">'.CMS::$Lang['inprogress']).'</button></td></tr>';
                               $i++;
                            }
                        }
                        ?>
                    </table></div>
				</div>
				
			</div>
        </div>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/vault.js"></script>
<?php
$this->WriteInc('Footer');
?>