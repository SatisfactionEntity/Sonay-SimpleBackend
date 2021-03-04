 <script>
        var SimpleCMS = {
            avatar: '<?= CMS::$Config['cms']['avatarlocation'] ?>'
        }
    </script>
	<style>
    input.lock {
        background: url(/Simple/Assets/img/lock.gif) no-repeat;
        background-position: 5px 50%;
        padding-left: 27px;
    }
    input[data-id="5"] {
        background: url(/Simple/Assets/img/5.gif) no-repeat;
        background-position: 5px 50%;
        padding-left: 27px;
    }
    input[data-id="0"] {
        background: url(/Simple/Assets/img/0.gif) no-repeat;
        background-position: 5px 50%;
        padding-left: 27px;
    }
	
	.error {
	background: #D30000;
    border-radius: 5px;
    color: white;
    min-height: 10px;
    display: none;
    width: 100%;
    padding: 5px;
    margin-bottom: 10px;
	}
</style>

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
					<div class="title-box png20" style="background-color:#00796B;background-image:url(/Simple/Assets/img/gamble.png);background-repeat: no-repeat;height: 80px;background-position: right;">
						<div class="title2"><font color="white">Lever Je Voucher In</font></div>
						<div class="desc2"><font color="white">Aah mooi 'n <?= CMS::$Config['cms']['hotelname'] ?> Voucher</font></div>
					</div>
                    <div class="inner">
                <div id="err1" class="error"></div>
                <b>Voucher code:</b>
                <div class="form-group">
                    <input type="text" class="lock form-control" placeholder="Vul hier jouw voucher code in." value="">
                </div>
                <button style="width:100%" id="claim" class="btn green">Inleveren!</button>
                <div style="clear:both"></div>
            </div>
                </div>
				<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:brown;background-image:url(/Simple/Assets/img/index_1.png);background-repeat: no-repeat;height: 80px;background-position: right;">
						<div class="title2"><font color="white">Maak Voucher Aan</font></div>
						<div class="desc2"><font color="white">Maak je eigen <?= CMS::$Config['cms']['hotelname'] ?> Vouchers aan</font></div>
					</div>
					<div class="png20 stataantal" style="max-height:750px;overflow-y: auto;">
						<div class="inner">
                <div id="err2" class="error"></div>
                <b>Hoeveel Diamanten:</b>
                <div class="form-group">
                    <input type="text" min="0" data-id="5" class="form-control" placeholder="Je hebt momenteel <?= Users::$Session->Data['5'] ?> Diamanten." value="">
                </div>
                <b>Hoeveel Duckets:</b>
                <div class="form-group">
                    <input type="text" min="0" data-id="0" class="form-control" placeholder="Je hebt momenteel <?= Users::$Session->Data['0'] ?> Duckets." value="">
                </div>
                <button style="width:100%" id="finish" class="btn green">Voltooien!</button>
                <div style="clear:both"></div>
            </div>

									</div>
				</div>
				</div>
				
			<div class="col-8">
				
			
				
				<div style="clear: both;"></div>
					
				
			<div id="shadow-box" style="max-height:100%">
					<div class="title-box png20" style="background-color:#7B1FA2;background-image:url(/Simple/Assets/img/index_2.png);background-repeat: no-repeat;height: 80px;background-position: right;">
						<div class="title2"><font color="white">Standaard Informatie</font></div>
						<div class="desc2"><font color="white">Uitleg nodig over <?= CMS::$Config['cms']['hotelname'] ?> Vouchers? Dat kan! </font></div>
					</div>
					<div class="png20 stataantal">
					
                    <div class="inner">
                <img src="/Simple/Assets/img/frankwithpresent.png" align="right" style="margin-top: 10px;margin-right: 20px;">
                Een voucher is een virtuele kaart die je kunt opladen met Diamanten en/of Duckets. Als je op "Voucher Indienen" hebt geklikt, krijg je een unieke voucher code. Alleen jij bent in het bezit van deze unieke
                voucher code. Verlies deze niet!<br>
                <br>
                Je kan ook een voucher code cadeau doen aan een vriend(in). Ditzelfde geldt ook andersom: Iemand anders kan jou ook een voucher code cadeau doen. Heb je een code ontvangen van een vriend(in), dan kan deze hier ingediend worden. <br>
                <br><b>Letop!</b>
                <br> - Een voucher aanmaken kost <b>5</b> Diamanten.
                <br> - Een voucher moet minimaal <b>25</b> Diamanten of Duckets bij elkaar bevatten.
                <br> - Een aangemaakte voucher is <b>1</b> maand geldig.</div>
                    <a href="#">
                        <button style="font-size:15px;width:100%" class="btn big green">Voucher Regels</button>
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
<script type="text/javascript" src="/Simple/Assets/js/loaders/voucher.js"></script>

