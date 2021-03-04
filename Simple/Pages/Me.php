<link rel="stylesheet" href="/Simple/Assets/css/Sonay/style.css?<?= time() ?>">
<link type="text/css" href="/Simple/Assets/css/Sonay/profile.css?v=9" rel="stylesheet">
    <script>
        var SimpleCMS = {
            avatar: '<?= CMS::$Config['cms']['avatarlocation'] ?>',
            maxtags: '<?= CMS::$Config['cms']['maxtags'] ?>'
        }
    </script>
		<div class="row">
        <link type="text/css" href="/templates/david_vox/css/profile.css?v=9" rel="stylesheet">
        <!--<div class="col-12">
			<div id="shadow-box" style="background-color:#52be80;height: 60px;">
					<div class="title-box png20">
						<center>
							<div class="desc"><font color="white" style="color: white"><b>Feit van de dag</b> Wist jij al dat Nathanmoore & Harmke ongeveer 600 hartjes per dag naar elkaar sturen?! Klef hoor!</font></div>
						</center>
					</div>
			</div>
		</div>-->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
   <script>
	$(function(){
		$("a[rel='tab']").click(function(e){
			pageurl = $(this).attr('href');
			$.ajax({url:pageurl+'?rel=tab',success: function(data){
				$('#content').html(data);
			}});
			if(pageurl!=window.location){
				window.history.pushState({path:pageurl},'',pageurl);	
			}
			return false;  
		});
	});
	$(window).bind('popstate', function() {
		$.ajax({url:location.pathname+'?rel=tab',success: function(data){
			$('#content').html(data);
		}});
	});
   </script>
        <div class="col-6" width="100%">
            <div id="shadow-box" class="profile" style="min-height: 330px;">
                <div class="bg" style="min-height: 330px;"></div>
                <div class="overlay" style="min-height: 330px;">
                    <div class="avatar-image" style="background-image:url(https://habbo.city/habbo-imaging/avatarimage?figure=<?= Users::$Session->Data['look'] ?>&size=l&head_direction=2&gesture=sml)"></div>

                    <div class="username">Hey, <?= Users::$Session->Data['username'] ?>!</div>
                    <div class="motto"><b></b><?= Users::$Session->Data['motto'] ?></div></div>

                    <div class="last-online">
                  
                    
                </div>
                <div style="clear:both"></div>
            </div>
			<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:red;background-image:url(/Simple/Assets/img/rijk.png);background-repeat: no-repeat;background-position: right;height: 80px;">
						<div class="title2"><font color="white">Jouw Currency</font></div>
						<div class="desc2"><font color="white">Jouw <?= CMS::$Config['cms']['hotelname'] ?> valuta!</font></div>
					</div>
					<div class="png20 stataantal" style="text-align: center;">
					<p style="text-align: right: 30px;;">
					
					<img src="/Simple/Assets/img/credits.png" width="15px">
					<b style="20px;"><?= number_format(Users::$Session->Data['credits'], 0, ',', '.') ?><br>
					<img src="/Simple/Assets/img/duckets.png" width="15px">
					<?= number_format(Users::$Session->Data['0'], 0, ',', '.') ?><br>
					<img src="/Simple/Assets/img/diamonds.png" width="14px" title="SS Punten">
					<?= number_format(Users::$Session->Data['5'], 0, ',', '.') ?>
					</b>
					<hr>
					<p style="15px;">Dit is je currency box, ga verstandig om met je in-game valuta.</p>
					</p>
					</div>
				</div>
			<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#7B1FA2;background-image:url(/Simple/Assets/img/spambox.png);background-repeat: no-repeat;background-position: right;height: 80px;">
						<div class="title2"><font color="white">Nodig vrienden uit</font></div>
						<div class="desc2"><font color="white">Nodig jouw vrienden uit naar <?= CMS::$Config['cms']['hotelname'] ?>!</font></div>
					</div>
					<div class="png20 stataantal">
					<img style="float:right;" src="/Simple/Assets/img/habbo_friends.png"/>
                    <p class="refer-text">
                        Iedereen wil het natuurlijk gezellig op hebben. En met veel online en al je vrienden
                        in het hotel gaat dat zeker lukken! <br/><br/>
                        Nodig daarom je vrienden uit en ontvang coole cadeau's <br>
                    </p>
                    <a data-target="verdienen">
                        <button style="font-size:15px;width:100%" class="btn big green">Meer informatie!</button>
                    </a>
					</div>
				</div>
				
		
				
        </div>
        <div class="col-6">
				
				
				
				<div id="shadow-box" style="background-color:#52be80;background-image:url(/Simple/Assets/img/naarhotel.png);background-repeat: no-repeat;background-position: right;height: 95px;">
					<a href="/game" target="_blank" style="text-decoration:none">
					<div class="title-box png20">
						<div class="title">
						<font color="white" style="color: white;font-size: 175%">
						Naar <?= CMS::$Config['cms']['hotelname'] ?></font></div>
						<div class="desc"><font color="white" style="color: white">en speel met alle andere <?= Site::GetUsersOnline() ?> <?= CMS::$Config['cms']['hotelname'] ?>ers!</font></div>
					</div>
					</a>
				</div>
				
				<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#7B1FA2;background-image:url(/Simple/Assets/img/dj.gif);background-repeat: no-repeat;height: 80px;background-position: right;">
						<div class="title2"><font color="white">Radio van Dox</font></div>
						<div class="desc2"><font color="white">Beste <?= CMS::$Config['cms']['hotelname'] ?> muziek begint hier!</font></div>
					</div>
					<div class="png20 stataantal">
					<center>
				<style>

				.now-playing-title {
					color:black;
				}

				</style>
				<iframe src="https://azuracast2.streams.ovh/public/doxfm/embed" frameborder="0" allowtransparency="true" style="width: 80%; min-height: 150px; border: 0; margin-top:20px; color:black;"></iframe>

				</center>
                    <!--<a href="/komtnog">
                        <button style="font-size:15px;width:100%" class="btn big green">Meer informatie!</button>
                    </a>!-->
					</div>
				</div>

				<div class="col-13">
					<?php $MyTags = 'Ga weg errors'; error_reporting(0); ?>
                </div>
                <div class="box">
                    <h2 class="title" style="background-color:green;background-image:url(/Simple/Assets/img/community.png);background-repeat: no-repeat;height: 80px;background-position: right;"></h2>
                    <div class="desc"><font color="white">Jouw opgelopen waarschuwingen.</font></div>
                    <div class="panel-body panel-mytags">
                        <div class="error TagError" style="display:none"></div>
                        <h2 class="refer-text" style="color: black;">Béta Systeem</h2>
                        <div <?= (count((array)$MyTags) >= CMS::$Config['cms']['maxtags'] ? ' style="display:none"' : '') ?>>Je hebt nog veel meer plek voor tags. Voeg er nog een paar toe!</div>
                        <div <?= (count((array)$MyTags) < CMS::$Config['cms']['maxtags'] ? ' style="display:none"' : '') ?>>Je hebt het maximaal aantal tags bereikt. Wis een van je interesses om een nieuwe toe te voegen.</div>
                        <hr>
                        <ul class="tag-list make-clickable" style="padding:0px">
                            <?php
							$MyTags = CMS::$MySql->column('SELECT tag FROM cms_tags WHERE userid = :userid', array('userid' => Users::$Session->ID));
                            if ($MyTags) {
                                foreach ($MyTags as $MyTag) {
                                    echo '<li class="tag" data-identifier="'.$MyTag.'"><a href="/tag/'.$MyTag.'" class="tag" style="font-size: 10px;">'.$MyTag.'</a><a class="tag-remove-link" data-name="'.$MyTag.'"></a></li>';
                                }
                            } ?>
                        </ul>
                        <div id="addtagpart"<?= (count((array)$MyTags) >= CMS::$Config['cms']['maxtags'] ? ' style="display:none"' : '') ?>>
                            <div class="form-group">
                                <input class="tag-name form-control" name="tag">
                            </div>
                            <div class="form-group">
                                <button id="addtag" class="btn btn-success">Voeg tag toe</button>
                            </div>
                            <div class="taghelp">
                                <center>
                                    <em><?= CMS::$Lang['taghelpbefore'] . CMS::$Lang['taghelp'.rand(1, 14)] ?></em>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
                
				
				
                
        				
        				
        </div>
        
        </div>
           
        </div>
        <div class="col-md-4">
            <?php if (CMS::$Config['cms']['radio']) { ?>
                <div class="box">
                <h2 class="blue"><?= CMS::$Config['cms']['hotelname'] ?> radio</h2>
                    <style>#toggler{margin:0px;display:block;float:left;width:18px;height:33px;}input[type=range]{width:60%;height:33px;margin:0px 0px 0px 0px;border-style:groove;}input[type=range]:focus{border-style:groove;}</style>
                <center>
                    <div>
                        <audio type = "audio/mpeg" id="streamPlayer" src="<?= CMS::$Config['cms']['radiostream'] ?>" preload="none"></audio>
                        <span onclick = "javascript:toggleRadio();" style = "display:inline-block;margin-right:12px">
                            <img style="cursor:pointer" id="toggler" data-status="<?= Users::$Session->Data['radio'] ?>" src="/Simple/Assets/img/play.png">
                        </span>
                        <input id = "volume" min = "0.0" max = "0.5" step = "0.01" type = "range" ondrag = "">
                    </div>
                </center>
                <script type = "text/javascript" >
                    var stream = document.getElementById("streamPlayer");
                    var toggler = document.getElementById("toggler");
                    var volume = document.getElementById("volume");

                    $(document).ready(function() {
                        stream.volume = 0.1;
                        if ($('#toggler').data('status')) {
                            toggler.src = "/Simple/Assets/img/pause.png";
                            stream.play();
                        } else {
                            stream.pause();
                        }
                    });

                    function toggleRadio()
                    {
                        if (stream.paused) {
                            stream.play();
                            toggler.src = "/Simple/Assets/img/pause.png";
                        } else {
                            stream.pause();
                            toggler.src = "/Simple/Assets/img/play.png";
                        }
                    }
                    volume.oninput = function () {
                        if (stream.paused) {
                            stream.play();
                            toggler.src = "/Simple/Assets/img/pause.png";
                        }
                        stream.volume = volume.value;
                    }

				</script>
                </div>
            <?php } $MyTags = CMS::$MySql->column('SELECT tag FROM cms_tags WHERE userid = :userid', array('userid' => Users::$Session->ID)); ?>

            <br>
            <br>
            
            
            <div style="clear: both;"></div>
        </div>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/me.js"></script>
