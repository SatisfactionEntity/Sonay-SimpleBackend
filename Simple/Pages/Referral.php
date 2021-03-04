<?php
$this->WriteInc('Header');
$Referral = CMS::$MySql->row('SELECT COUNT(*) AS invited, (SELECT level FROM cms_referral_rewards WHERE friends_needed <= COUNT(*) ORDER BY level DESC LIMIT 1) AS level FROM cms_referral WHERE target_id = :userid', array('userid' => Users::$Session->ID));
?>
<script type="text/javascript" src="/Simple/Assets/js/global/clipboard.min.js"></script>
<link rel="stylesheet" href="/Simple/Assets/css/Sonay/style.css?<?= time() ?>">
<style>
    .box {
        padding: 0;
    }
    .box h2 {
        margin:0;
    }
</style>
   <div class="container">
        <div class="row">
			<div class="col-5">
<!-- BEGIN EZMOB TAG -->
<SCRIPT TYPE="text/javascript">
var __jscp=function(){for(var b=0,a=window;a!=a.parent++b,a=a.parent;if(a=window.parent==window?document.URL:document.referrer)
{var c=a.indexOf("://");0<=c&&(a=a.substring(c+3));c=a.indexOf("/");0<=c&&(a=a.substring(0,c))}
var b={pu:a,"if":b,rn:new Number(Math.floor(99999999*Math.random())+1)},a=[],d;for(d in b)a.push(d+"="+encodeURIComponent(b[d]));return encodeURIComponent(a.join("&"))};
document.write(\'<S\' + \'CRIPT TYPE="text/javascript" SRC="//cpm.ezmob.com/tag?zone_id=109704&size=728x90&subid=&j=\' + __jscp() + \'"></S\' + \'CRIPT>\');
</SCRIPT>
<!-- END TAG -->
                                
				
            		
				<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:blue;background-image:url(/Simple/Assets/img/vips.png);background-repeat: no-repeat;height: 80px;background-position: right;">
						<div class="title2"><font color="white">Je Invite Level</font></div>
						<div class="desc2"><font color="white">Je prachtige <?= CMS::$Config['cms']['hotelname'] ?>teller</font></div>
					</div>
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
				
                    
				<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:red;background-image:url(/Simple/Assets/img/expert.png);background-repeat: no-repeat;height: 80px;background-position: right;">
						<div class="title2"><font color="white">Jouw Status</font></div>
						<div class="desc2"><font color="white">Weet jij hoeveel je nog moet? Kijk snel hieronder!</font></div>
					</div>
                     <div class="png20 stataantal" style="max-height:750px;overflow-y: auto;">
					<img src="/Simple/Assets/img/level.gif"> Level:
                        <span style="float:right;margin-top:11px">Level <?= (int)$Referral['level'] ?></span>
						<br>
						<br>
						<img src="/Simple/Assets/img/link.gif"> Vrienden:
                        <span style="float: right;margin-top: 11px;">
                        <?= '<span id="invited">' . $Referral['invited'] . '</span> ' . ($Referral['invited'] == 1 ? CMS::$Lang['friend'] : CMS::$Lang['friends']); ?>
                        </span>
					 </div>
                </div>
				</div>
			<div class="col-7">
				
			
				
				<div style="clear: both;"></div>
					
				<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:brown;background-image:url(/Simple/Assets/img/vault.png);background-repeat: no-repeat;height: 80px;background-position: right;">
						<div class="title2"><font color="white">Jouw persoonlijke Referral Url</font></div>
						<div class="desc2"><font color="white">Gebruik deze goed voor veel prijzen :)</font></div>
					</div>
					<div class="png20 stataantal" style="max-height:750px;overflow-y: auto;">
						<?= CMS::$Config['cms']['hotelname'] ?> is dankbaar wanneer jij een andere gebruiker <?= CMS::$Config['cms']['hotelname'] ?> laat spelen. Daarom willen we je graag daarvoor belonen.
                    <br><br>
                    Je kunt hiernaast een speciale referral link vinden.
                    Stuur deze link naar anderen en nadat ze zich geregistreerd hebben bij <?= CMS::$Config['cms']['hotelname'] ?> krijg je punten. Hoe meer mensen je verwijst, hoe beter je cadeau's zullen zijn!
                    <br><br>
					
                    <p style="text-align: center">
                        <a id="foo" href="#"><?= CMS::$Config['cms']['url'] . '/r/' . Users::$Session->ID ?></a>
                        <input style="display:none" id="foo" type="text" value="<?= CMS::$Config['cms']['url'] . '/r/' . Users::$Session->ID ?>">
                        <button class="btn btn-success" data-clipboard-action="copy" data-clipboard-target="#foo">Kopieer
                        </button>
                    </p>

						<hr>					</div>
				</div>
			
			<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#7B1FA2;background-image:url(/Simple/Assets/img/party.png);background-repeat: no-repeat;height: 80px;background-position: right;">
						<div class="title2"><font color="white">Prijzen</font></div>
						<div class="desc2"><font color="white">Openstaande prijzen!</font></div>
					</div>
					<div class="png20 stataantal">
                    <p class="refer-text">
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
                    </table>
                    </p>
                    
					</div>
				</div>
		</div>
		
    </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>
       
    <script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/referral.js"></script>
<?php
$this->WriteInc('Footer');
?>