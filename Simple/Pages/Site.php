<?php
$this->WriteInc('Header');
?>
<link rel="stylesheet" href="/Simple/Assets/css/Sonay/style.css?<?= time() ?>">
<link type="text/css" href="/Simple/Assets/css/Sonay/profile.css?v=9" rel="stylesheet">
<div class="container" id="content" width="100%">
      <div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#00796B;background-image:url(/Simple/Assets/img/gym.png);background-repeat: no-repeat;background-position: right;background-size: cover;height: 80px;">
						<div class="title2"><font color="white">Website van Brighthotel</font></div>
						<div class="desc2"><font color="white">Uitleg over onze website werking.</font></div>
					</div>
					<div class="png20 stataantal">
						<img src="/Simple/Assets/img/naarhotel.png" align="right" style="margin-top: 10px;margin-right: 20px;">
						<h3>Welkom op Brighthotel, <b><?= Users::$Session->Data['username'] ?></b>.</h3>
						<br>
                Zoals elk ander hotel bestaat een retro hotel ofja website uit meerdere bestanden, en waarmee ook de url veranderd. Bij brighthotel.nl is dat niet zo. Dus we hebben een category van 2 delen.
				
				<br>
				<br>
				<b><p>1. /site <-- Alle site content ook je settings. </p></b>

				<b><p>2. /game <-- Alle client gebeuren.</p></b>
				<hr>
                <h3>Hoe maak je er gebruik van?</h3>
				<span>Heel simpel klik op een bestemming waar je heen wilt gaan op de navbar.</span>
											
											</ul>
										<p></p>
    		            </table>
					</div>
				</div>
			</div>
    </div>
	
<script type="text/javascript" src="/Simple/Assets/js/global/script.js?<?= time() ?>"></script>
<?php
$this->WriteInc('Footer');
?>