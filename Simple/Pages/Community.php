
    <script>
        var SimpleCMS = {
            avatar: '<?= CMS::$Config['cms']['avatarlocation'] ?>'
        }
    </script>

<link rel="stylesheet" href="/Simple/Assets/css/Sonay/style.css?<?= time() ?>">
<link type="text/css" href="/Simple/Assets/css/Sonay/profile.css?v=9" rel="stylesheet">
   
        <div class="row">
			<div class="col-4">
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
					<div class="title-box png20" style="background-color:brown;background-image:url(/Simple/Assets/img/community.png);background-repeat: no-repeat;height: 80px;background-position: right;">
						<div class="title2"><font color="white">Populaire Kamers</font></div>
						<div class="desc2"><font color="white">Populaire kamers in <?= CMS::$Config['cms']['hotelname'] ?></font></div>
					</div>
					<div class="png20 stataantal" style="max-height:750px;overflow-y: auto;">
						<?php
                    if (!$Rooms = CMS::$Cache->get('popularrooms')) {
                        $Rooms = CMS::$MySql->query('SELECT rooms.id, users.username, name, users FROM rooms LEFT JOIN users ON users.id = owner_id WHERE users > 0 ORDER BY users DESC LIMIT 25');
                        CMS::$Cache->set('popularrooms', $Rooms ? $Rooms : $Rooms = 'none', 30);
                    }
                    if (is_array($Rooms)) {
                        echo '<div style="height:190px;margin-top:10px" class="scroll">';
                        foreach ($Rooms as $Row) {
                            echo '<div onclick="enterroom('.$Row['id'].');" class="room-top icon2"><div class="users_now">' . $Row['users'] . '</div><div class="caption">' . htmlspecialchars($Row['name']) . '</div><div class="owner">Eigenaar: <b>' . $Row['username'] . '</b></div></div>';
                        }
                    } else {
                        echo '<div class="inner">'.CMS::$Lang['noroomsfound'];
                    }
                    ?>

						<hr>					</div>
				</div>
			</div>
			<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#7B1FA2;background-image:url(/Simple/Assets/img/habbo_friends.png);background-repeat: no-repeat;height: 80px;background-position: right;">
						<div class="title2"><font color="white">Nodig vrienden uit</font></div>
						<div class="desc2"><font color="white">Nodig jouw vrienden uit naar <?= CMS::$Config['cms']['hotelname'] ?>!</font></div>
					</div>
					<div class="png20 stataantal">
					<img style="float:right;width: 150px;" src="/Simple/Assets/img/habbo_friends.png"/>
                    <p class="refer-text">
                        Iedereen wil het natuurlijk gezellig op <?= CMS::$Config['cms']['hotelname'] ?> hebben. En met veel online en al je vrienden
                        in het hotel gaat dat zeker lukken! <br/><br/>
                        Nodig daarom je vrienden uit en ontvang coole cadeau's <br>
                    </p>
                    <a href="/verdienen">
                        <button style="font-size:15px;width:100%" class="btn big green">Meer informatie!</button>
                    </a>
					</div>
				</div>
		</div>
		
    </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/community.js"></script>
