<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title><?= CMS::$Config['cms']['hotelname'] ?>: <?php echo $this->Title ?></title>
    <link href='https://fonts.googleapis.com/css?family=Exo+2' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/Simple/Assets/css/global/font-awesome.min.css">
    <link rel="stylesheet" href="/Simple/Assets/css/global/bootstrap.min.css">
    <link rel="stylesheet" href="/Simple/Assets/css/global/style.css?<?= time() ?>">
    <link rel="shortcut icon" href="/Simple/Assets/img/favicon.ico" type="image/vnd.microsoft.icon"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="/Simple/Assets/js/global/jquery.min.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/global/jquery-ui.min.js"></script>
	<script src="/Simple/Assets/js/custom/JQuery_Load.js"></script>
</head>
<body>
<header style="background-color:dodgerblue;">

    <div class="hotel"></div>
    <div id="clouds"></div>
    
    <div style="cursor:pointer" onclick="window.location='/'" class="big-logo">
    </div>
	<script>
      
    </script>

    <?php if (Users::$Session == true) { ?>
        <div class="head_enter">
            <a style="text-transform: none;" href="/game" target="_blank" class="btn btn-success">Naar <?= CMS::$Config['cms']['hotelname'] ?>
                </a> <a style="text-transform: none;" onclick="logout()" href="#" class="btn btn-danger">Log
                uit</a>
        </div>
		
        <div class="wrap">
            <nav id="nav">
                <ul class="main">
                    <li class="menu-button"><i class="fa fa-bars" style="cursor:pointer;"></i></li>
                </ul>
                <ul class="left">
				
                    <li class="">
                        <i class="icon home"><a data-target="me" href="/">ME</a></i>
                        <div class="submenu"><!---<a href="/home/<?= Users::$Session->Data['username'] ?>">Mijn pagina</a><a href="/referral">Mijn verdiensten</a><a onclick="getGiftData(true);" href="#">Dagelijks cadeau</a><a href="/profile">Jouw instellingen</a>!--><a onclick="logout()" href="#">Uitloggen</a></div>
                    </li>
                    <li class="">
                        <i class="icon community"><a data-target="community">GEMEENSCHAP</a></i>
						
                        <div class="submenu"><a data-target="foto">Bright Foto's</a><!---<a href="/news"><?= CMS::$Config['cms']['hotelname'] ?>
                                Nieuws</a><a href="/top">Top 5 lijst</a><a href="/tag">Tags</a></div>
                    </li>
					
                    <li class="">
                        <i class="icon staff"><a href="/staff">MEDEWERKERS</a></i>
                        <div class="submenu">
                            <a href="/staff">Wie zijn de <?= CMS::$Config['cms']['hotelname'] ?> staff</a>
                            <a href="/gambleteam">Gok Team</a>
                            <a href="/eventteam">Event Team</a>
                            <a href="/bouwteam">Bouw Team</a>
                            <a href="/spamteam">Spam Team</a>
                            <a href="/djteam">DJ Team</a>
                        </div>
                    </li>
                    <li class="">
                        <i class="icon diamonds"><a href="/badges">VALUTA</a></i>
                        <div class="submenu"><a href="/badges">Badge shop</a><a href="/packages">Pakketten kopen</a><a href="/voucher">Voucher</a></div>
                    </li>
					!-->
                    <li class="">
                        <i class="icon extra"><a data-target="vault">EXTRA</a></i>
                        <div class="submenu"><a data-target="vault">Kraak de kluis</a><a data-target="voucher">Vouchers</a><!---<a href="/values">Ruil waardes</a><a href="/changename">Verander naam</a><a href="/applications">Solliciteren</a></div>
                    </li>
					<li class="">
                        <i onclick="discord()" class="icon discord"><a href="https://discord.gg/PFkxECC">Discord</a></i>
                    </li>!-->
                    <?php if (Users::$Session->HasPermission(CMS::$Config['manage']['dashboard'])) {
                        echo '<li class="">
						<i class="icon admin"><a href="/manage/dashboard">ADMIN</a></i>
						</li>';
                    } ?>
                </ul>
            </nav>
			
    <?php } else { ?>
        <div class="facebook">
        </button>
            <a href="/register">
                <button type="button" class="big-register">REGISTREER JE GRATIS<span>»</span>
            </a></button></div>
        <div class="wrap">
            <nav>
                <ul class="main">
                    <li class="menu-button"><i class="fa fa-bars" style="cursor:pointer;"></i></li>
                </ul>
                <ul class="left">
                    <li class=""><i class="icon home"><a href="/">LOG IN</a></i></li>
                    <li class=""><i class="icon community"><a href="/register">REGISTREER JE GRATIS</a></i></li>
                </ul>
            </nav>
        </div>
        <script type="text/javascript" src="/Simple/Assets/js/global/script.js?<?= time() ?>"></script>
        <script type="text/javascript" src="/Simple/Assets/js/global/social.js"></script>
        <script>
            $(document).ready(function () {
                window.fbAsyncInit = function () {
                    FB.init({
                        appId: '<?= CMS::$Config['cms']['facebookappid'] ?>',
                        cookie: true,
                        version: 'v2.9'
                    });
                }
            });
        </script>
    <?php } ?>
    
</header>
    
</div>

</div>