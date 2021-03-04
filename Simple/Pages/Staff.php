
<?php
$this->WriteInc('Header');
?>
    <script>
        var SimpleCMS = {
            avatar: '<?= CMS::$Config['cms']['avatarlocation'] ?>'
        }
    </script>

<link rel="stylesheet" href="/Simple/Assets/css/Sonay/style.css?<?= time() ?>">
<link type="text/css" href="/Simple/Assets/css/Sonay/profile.css?" rel="stylesheet">
   <div class="container">
        <div class="row">
			<div class="col-2">
<!-- BEGIN EZMOB TAG -->
<SCRIPT TYPE="text/javascript">
var __jscp=function(){for(var b=0,a=window;a!=a.parent++b,a=a.parent;if(a=window.parent==window?document.URL:document.referrer)
{var c=a.indexOf("://");0<=c&&(a=a.substring(c+3));c=a.indexOf("/");0<=c&&(a=a.substring(0,c))}
var b={pu:a,"if":b,rn:new Number(Math.floor(99999999*Math.random())+1)},a=[],d;for(d in b)a.push(d+"="+encodeURIComponent(b[d]));return encodeURIComponent(a.join("&"))};
document.write(\'<S\' + \'CRIPT TYPE="text/javascript" SRC="//cpm.ezmob.com/tag?zone_id=109704&size=728x90&subid=&j=\' + __jscp() + \'"></S\' + \'CRIPT>\');
</SCRIPT>
<!-- END TAG -->
                                
				
            		
				<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#00796B;background-image:url(/Simple/Assets/img/expert.png);background-repeat: no-repeat;height: 80px;background-position: right;">
						<div class="title2"><font color="white">Populaire Groepen</font></div>
						<div class="desc2"><font color="white">Populaire groepen in <?= CMS::$Config['cms']['hotelname'] ?></font></div>
					</div>
                    <?php
                    if (!$Groups = CMS::$Cache->get('populargroups')) {
                        $Groups = CMS::$MySql->query('SELECT guilds.id,name,users.username,room_id,badge,COUNT(guilds_members.id) AS members FROM guilds LEFT JOIN guilds_members ON guilds_members.guild_id = guilds.id LEFT JOIN users ON users.id = guilds.user_id GROUP BY guilds.id ORDER BY COUNT(guilds_members.id) DESC LIMIT 10');
                        CMS::$Cache->set('populargroups', $Groups ? $Groups : $Groups = 'none', 3600);
                    }
                    if (is_array($Groups)) {
                        echo '<div style="height:190px;margin-top:10px" class="scroll">';
                        foreach ($Groups as $Row) {
                            echo '<div style="background-image:url('.CMS::$Config['cms']['groupbadgemap'].'/'.$Row['badge'].'.png)" onclick="enterroom(' . $Row['room_id'] . ');" class="group-top icon"><div class="members">' . $Row['members'] . '</div><div class="caption">' . htmlspecialchars($Row['name']) . '</div><div class="description">Eigenaar: <b>'.$Row['username'].'</b></div></div>';
                        }
                    } else {
                        echo '<div class="inner">'.CMS::$Lang['nogroupsfound'];
                    }
                    ?>
                </div>
				</div>
                    
				
				</div>
			<div class="col-8">
				
			
				
				<div style="clear: both;"></div>
					
				<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#00796B;background-image:url(/Simple/Assets/img/community.png);background-repeat: no-repeat;height: 80px;background-position: right;">
						<div class="title2"><font color="white">Populaire Kamers</font></div>
						<div class="desc2"><font color="white">Populaire kamers in <?= CMS::$Config['cms']['hotelname'] ?></font></div>
					</div>
					<div class="png20 stataantal" style="max-height:750px;overflow-y: auto;">
                    <?php
$i = 0;
$one = '';
$two = '';
$three = '';
$Players = CMS::$MySql->query('SELECT username,look,last_online,motto,online,rank FROM users WHERE hidden = "0" AND rank in (30,28,26,25,24,23) ORDER BY rank DESC');
$Ranks = CMS::$MySql->query('SELECT id,rank_name,color FROM permissions WHERE id in (30,28,25,26,24,23) ORDER BY id DESC');
if (is_array($Ranks)) {
    foreach ($Ranks as $Row) {
        $found = false;
        $Data = '<div class="box"><h2 class="staff title_' . $Row['color'] . '">' . $Row['rank_name'] . '</h2>';
        if (is_array($Players)) {
            foreach ($Players as $Player) {
                if ($Player['rank'] == $Row['id']) {
                    $found = true;
                                       $Data .= '<div class="staffBox"><img style="margin-top:-15px; position:absolute" src="'.CMS::$Config['cms']['avatarlocation'].'?figure=' . $Player['look'] . '&direction=3&head_direction=3&gesture=sml&action=wav"><span class="status"><img style="position: absolute; right: 4px;" src="/Simple/Assets/img/' . ($Player['online'] == '1' ? 'online' : 'offline') . '.gif"></span><table style="margin-left: 70px; padding-top: 6px;"><tr style="padding: 0;"><td><strong><a href="/home/' . $Player['username'] . '">' . $Player['username'] . '</a></strong><br>' . htmlspecialchars($Player['motto']) . '<br>Laatst online: ' . date('d-m-Y H:i:s', $Player['last_online']) . '</td></tr></table></div>';

                }
            }
        }
        if (!$found) {
            $Data .= '<div class="inner">' . str_ireplace("%rank%", $Row['rank_name'], CMS::$Lang['ranknotfound']) . '</div>';
        }
        $Data .= '</div>';

        if ($i == 0) {
            $one .= $Data . '<div class="box"><div class="inner info"><img src="' . CMS::$Config['cms']['badgemap'] . '/ADM.gif" align="right">' . str_ireplace("%hotelname%", CMS::$Config['cms']['hotelname'], CMS::$Lang['staffpage']) . '</p></div></div><div class="box"><div class="inner info">' . str_ireplace("%hotelname%", CMS::$Config['cms']['hotelname'], CMS::$Lang['staffpage2']) . '</div></div>';
        } else {
            $two .= $Data;
        }

            $i++;
        }
        echo '<div class="col-md-4">' . $one . '</div><div class="col-md-8">' . $two . '</div>';
}
else {
    echo '<div class="col-sm-7"><div class="box"><h2 class="red">'.CMS::$Lang['noranksfound'].'</h2><div class="inner">'.CMS::$Lang['noranksfound2'].'</div></div></div>';
}
?>

						<hr>					</div>
				</div>
			</div>
		
		
    </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/community.js"></script>


<!--
<div class="row">

</div>
<script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
!-->
<?php
$this->WriteInc('Footer');
?>
