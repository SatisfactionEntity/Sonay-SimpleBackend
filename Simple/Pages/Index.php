<?php
if (Users::$Session == true) {
    Site::Stop('/site');
}

?>
<link href='https://fonts.googleapis.com/css?family=Exo+2' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="/Simple/Assets/js/global/script.js?<?= time() ?>"></script>
        <script type="text/javascript" src="/Simple/Assets/js/global/social.js"></script>
        <script type="text/javascript" src="/Simple/Assets/js/global/jquery.min.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/global/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="/Simple/Assets/css/global/bootstrap.min.css">
    <link rel="stylesheet" href="/Simple/Assets/css/global/style.css?<?= time() ?>">
    <link rel="stylesheet" href="/Simple/Assets/css/global/index.css">
    <link rel="stylesheet" href="/Simple/Assets/css/global/index2.css">
    <script>
        var SimpleCMS = {
            avatar: '<?= CMS::$Config['cms']['avatarlocation'] ?>'
        }
    </script>

        <div class="hero">
            <div class="hotel"></div>
        </div>

        <div id="header-content">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <div class="logo"></div>
                        <div class="online-count"><b><?= Site::GetUsersOnline() ?></b> <?= CMS::$Config['cms']['hotelname'] ?>'s online</div>
                    </div>
                </div>
            </div>
        </div>
		<h2>Beta versie</h2>
		<span>We zijn nog bezig met de komende functies.</span>
        <div class="container">
            <div class="row">
                <div class="col-md-6" style="margin-top: 10px;">
                    <div class="login-position">
					<div style="display:none" class="alert alert-danger alert-dismissable fade in"><span id="message"></span></div>
					<div class="panel-body">
                    <h2>Aanmelden</h2>
                    <br><b><?= CMS::$Config['cms']['hotelname'] ?> Naam of e-mailadres</b><br>
                    <input id="username" type="text" class="login-field" maxlength="64" placeholder="Naam of e-mailadres..." required>
                    <div id="imager"></div>
                    <br><b>Wachtwoord</b><br>
                    <input type="password" id="password" maxlength="32" placeholder="Wachtwoord..." class="form-control" required>
                    <br>
                    <div style="display:none" id="twostepsbox">
                        <b>Tweestapsvertficatie code</b><br>
                        <input type="text" id="twosteps" maxlength="6" placeholder="Tweestapsvertficatie code..." class="form-control" required>
                        <br>
                    </div>
                                        <div style="display:none" class="form-group" id="captchabox">
                        <div id="captcha" style="transform:scale(0.93);-webkit-transform:scale(0.93);transform-origin:0 0;-webkit-transform-origin:0 0"></div><br>
                    </div>
                                            <div class="row">
                            <div class="col-md-6">
								<button id="login" class="btn green login-button" style="width:100%;">Log in</button>
                            </div>
                            <div class="col-md-6">
								<button onclick="ForgotPassword(); return false;" class="btn red login-button" style="width:100%;">Wachtwoord Vergeten?</button>
                            </div>
                        </div>
                        <div class="clear"></div>
                        <div class="row">
                            <div class="col-md-12">
                                <p>of</p>
                            </div>
                            <div class="col-md-9">
                                <a href="/register">
									<button class="btn orange register-button">Account Aanmaken</button>
								</a>
                            </div>
                            <div class="col-md-3">
                                <button class="btn fb register-button" onclick="FacebookLogin(); return false;" style="height:50px;"><i class="fab fa-facebook-square fa-lg"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
            <div id="footer-content">
            <div class="clear"></div>
            <?php
                    if (!$Tags = CMS::$Cache->get('populartags')) {
                        $Tags = CMS::$MySql->column('SELECT tag FROM cms_tags GROUP BY tag ORDER BY COUNT(id) DESC LIMIT '.CMS::$Config['cms']['maxpopulartags']);
                        CMS::$Cache->set('populartags', $Tags);
                    }
                    if ($Tags) {
                        echo '<ul class="tag-list">';
                        foreach ($Tags as $Tag) {
                            echo '<li style="font-size:120%"><a href="/tag/'.$Tag.'">'.$Tag.'</li></a>';
                        }
                        echo '</ul>';
                    }
                    else {
                        echo '<div class="inner">Geen tags gevonden.</div>';
                    }?>
        
<script type="text/javascript" src="/Simple/Assets/js/loaders/index.js"></script>
<?php if (CMS::$Config['cms']['captcha']) { ?>
    <script src="https://www.google.com/recaptcha/api.js?onload=myCallBack&render=explicit" async defer></script>
    <script>
            var captcha;
            var myCallBack = function () {
                captcha = grecaptcha.render('captcha', {
                    'sitekey': '<?= CMS::$Config['cms']['captchapublickey'] ?>',
                    'theme': 'light'
                });
            };
    </script>
<?php }

?>