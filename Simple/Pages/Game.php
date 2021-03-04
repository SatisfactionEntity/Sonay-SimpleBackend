
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?= CMS::$Config['cms']['hotelname'] . ': ' . $this->Title ?></title>
    <link rel="stylesheet" href="/Simple/Assets/css/client/client.css?<?= time() ?>">
    <link rel="stylesheet" href="/Simple/Assets/css/global/font-awesome.min.css">
    <link rel="shortcut icon" href="/Simple/Assets/img/favicon.ico" type="image/vnd.microsoft.icon"/>
</head>
<div class="client-buttons">
<button class="client-count rounded-button blue plain">
		<b>Vastegelopen</b>
	</button>
	<button class="client-fullscreen rounded-button blue plain" onclick="Fullscreen();">
		<i class="client-icon client-fullscreen-icon"></i>
		<i class="client-icon client-fullscreen-icon-back hidden"></i>
	</button>

	<button class="client-count rounded-button blue plain">
		<i class="fa fa-user"></i> <b id="count"><?= Site::GetOnline() ?></b>
	</button>

		<!---<button class="client-tv rounded-button blue plain" id="youtube_close_button" onclick="Closeyoutube();">
		<i class="fa fa-tv"></i>
	</button>!-->
</div>
	
<?php if (CMS::$Config['cms']['ads']) { ?>
    <div style="display:none" id="room" class="roomenterad-habblet-container">
        <div id="adTimer" class="roomenterad-closing1">Deze advertentie sluit over <b>30</b> seconden!</div>
        <div id="closeAd" class="roomenterad-closing1"><a href="#" onclick="closeAd();">Sluit advertentie</a></div>
        <div class="roomenterad-habblet-thead">
            <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
            <ins class="adsbygoogle" style="display:inline-block;width:728px;height:90px"
                 data-ad-client="<?= CMS::$Config['cms']['data-ad-client'] ?>"
                 data-ad-slot="<?= CMS::$Config['cms']['data-ad-slot'] ?>"></ins>
            <script>
                (adsbygoogle = window.adsbygoogle || []).push({});
            </script>
        </div>
    </div>
<?php } ?>
<center>
    <div id="client-ui">
        <div class="hb-container" id="area-container">
            <h1 class="text" id="client-title">Verbinding verbroken!</h1>
            <div id="no-flash" style="display: none;">
                <div id="info-allow"></div>
                <div id="info-allow-button" style="display: none; text-align: center;">
                    <a href="https://get.adobe.com/nl/flashplayer/" target="_blank" id="allow-flash-button-extern"
                       class="client-reload__button" style="display: none;">Flash Inschakelen</a>
                    <button id="allow-flash-button-more" class="client-reload__button"
                            style="display: none; background-color: #f44336;border-color: #d66d66;color: #fff;">Niet
                        gelukt?
                    </button>
                </div>
                <p id="info-flash">
                    Het kan zo zijn dat er geen flash is geïnstalleerd op je computer, telefoon of tablet. Helaas heb je
                    dit
                    wel
                    nodig om <?= CMS::$Config['cms']['hotelname'] ?> te spelen! Gelukkig is er hiervoor een oplossing.
                    <br/><br/>
                    Probeer de onderstaande stappen:<br/><br/>
                    <b style="font-weight: bold;">Zit je op je laptop of PC?</b>
                </p>
                <div id="info-flash-extra">
                    <ul>
                        <li>Download <a href="https://get.adobe.com/nl/flashplayer/">Adobe Flash Player</a> gratis.</li>
                    </ul>
                    <br/>
                    <b style="font-weight: bold;">Schakel Flash Player in op je browser</b>
                    <ul>
                        <li>Zie Flash Player inschakelen voor <a target="_blank"
                                                                 href="https://helpx.adobe.com/nl/flash-player/kb/enabling-flash-player-chrome.html">Google
                                Chrome</a>
                            voor informatie;
                        </li>
                        <li>Zie Flash Player inschakelen voor <a target="_blank"
                                                                 href="https://helpx.adobe.com/nl/flash-player/kb/install-flash-player-windows.html">Internet
                                Explorer</a> voor informatie;
                        </li>
                        <li>Zie Flash Player inschakelen voor <a target="_blank"
                                                                 href="https://helpx.adobe.com/flash-player/kb/flash-player-issues-windows-10-edge.html">Microsoft
                                Edge</a>
                            voor informatie;
                        </li>
                        <li>Zie Flash Player inschakelen voor <a target="_blank"
                                                                 href="https://helpx.adobe.com/nl/flash-player/kb/enabling-flash-player-firefox.html">Firefox</a>
                            voor
                            informatie;
                        </li>
                        <li>Zie Flash Player inschakelen voor <a target="_blank"
                                                                 href="https://helpx.adobe.com/nl/flash-player/kb/enabling-flash-player-safari.html">Apple
                                Safari</a>
                            voor informatie.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="client"
             style='position:absolute; left:0; right:0; top:0; bottom:0; overflow:hidden; height:100%; width:100%;'></div>
</center>
<div id="music-box" style="display: none;">
	<div id="jukebox">
		<div id="jukebox-songs">
			<div id="jukebox-current">Geen video</div> 
			<div id="jukebox-next">Volgende: <span class="value"></span></div>
		</div> 
		<div id="jukebox-controls"><i id="jukebox-play" class="fa fa-stop"></i> 
			<input type="range" min="0" max="100" value="100" id="jukebox-volume">
		</div>
	</div>
	<div class="jb-divider"></div>
	<div id="jukebox-tv-toggle"></div>
</div>
<div id="jukebox-player-wrapper" class="draggable ui-draggable" style="display: none;">
	<div id="jukebox-drag-bar" class="draggable-handler ui-draggable-handle"></div> 
	<div id="jukebox-tv-overlay"></div> 
	<div id="jukebox-player"></div>
</div>
<div id="jukebox-add" class="container closable draggable centre" style="display: none;">
    <div class="header">
        <h1>Liedje aanvragen</h1>
        <div class="close"></div>
    </div>
    <div class="body">
        <div class="form-group">
            <div class="form-title">YouTube Video URL of ID (youtube.com/watch?v=XXXXX of gewoon XXXXX)</div>
            <input type="text" class="text-box" id="jukebox-add-video-id" placeholder="Song ID"></input>
        </div>
        <div class="flexi-button" id="jukebox-queue-song-button">Aanvragen</div>
    </div>
</div><!--
<div id="socket-stats" class="draggable-self">
Socket: <strong><span id="socket-status" style="color: rgb(255, 0, 0);">Niet verbonden!</span></strong></div>!-->
</body>
</html>
<script>
    var HabboManager = "<?= base64_encode(Site::StringEncoder(implode('|', CMS::$Config['client']) . '|' . $_COOKIE['simple_user_hash'])) ?>";
</script>
<script src="/Simple/Assets/js/global/jquery.min.js"></script>
<script src="/Simple/Assets/js/global/jquery-ui.min.js"></script>
<script src="/Simple/Assets/js/client/moment.min.js"></script>
<script src="/Simple/Assets/js/client/jquery.timer.js"></script>
<script src="/Simple/Assets/js/client/flash_detect_min.js"></script>
<script src="/Simple/Assets/js/client/browse.js"></script>
<script src="/Simple/Assets/js/client/flashclient.js"></script>
<script src="/Simple/Assets/js/loaders/client.js?<?= time() ?>"></script>
<script>
var closed_youtube = 0;
var youtubeplayer = document.getElementById("music-box");

	//youtubeplayer.style.display = "block";

function Closeyoutube(){
	if(closed_youtube == 0){
				 $('#music-box').show();
		closed_youtube = 1;
	}else if(closed_youtube == 1){
		 $('#music-box').hide();
        $('#jukebox-player-wrapper').hide();
		closed_youtube = 0;
	}
}
</script>
