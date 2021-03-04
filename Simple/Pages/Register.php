<?php
if (Users::$Session == true) {
    Site::Stop('/me');
}
$this->WriteInc('Header');
?>
<style>
#clouds {
    background-image: url(https://doxhotel.eu/core/assets/images/clouds.png);
    position: absolute;
    left: 0;
    top: 50px;
    width: 100%;
    height: 134px;
    overflow: hidden;
    -webkit-animation: slide 60s linear infinite;
    -moz-animation: slide 60s linear infinite;
    -ms-animation: slide 60s linear infinite;
    -o-animation: slide 60s linear infinite;
    animation: slide 60s linear infinite;
}
    </style>
<div id="clouds"></div>
    <link rel="stylesheet" href="/Simple/Assets/css/global/index.css"><link rel="stylesheet" href="/Simple/Assets/css/global/style.css?<?= time() ?>">
    <link rel="stylesheet" href="/Simple/Assets/css/global/index2.css">
    <script>
        var SimpleCMS = {
            avatar: '<?= CMS::$Config['cms']['avatarlocation'] ?>'
        }
    </script>
    <br>
    <div class="row">
        <div class="col-md-2">
            
			
        </div>
        <div class="col-md-3">
        <div style="display:none; border-radius: 1em;" class="alert alert-danger alert-dismissable fade in"></div></center><br>
            <div class="box">
            
                <br><b>Naam:</b><br>
                <input id="username" class="tag-name form-control" placeholder="Naam..." required>
                <br><b>Wachtwoord:</b><br>
                <input type="password" id="password" class="tag-name form-control" placeholder="Wachtwoord..." required>
                <br><b>Wachtwoord herhalen:</b><br>
                <input type="password" id="password2" class="tag-name form-control" placeholder="Wachtwoord opnieuw..." required>
                <br>
                <b>E-mailadres:</b>
                <input id="email" class="tag-name form-control" placeholder="E-mailadres..." required>
                <br>
                                <div class="form-group">
                    <div id="captcha" style="transform:scale(0.93);-webkit-transform:scale(0.93);transform-origin:0 0;-webkit-transform-origin:0 0"></div>
                </div>
                                <button id="register" class="btn btn-success" style="width:100%">Account aanmaken</button><hr>
                <a href="/" class="btn btn-danger" style="width:100%;text-decoration:none;color:white;">Terug naar Inloggen</a>
            </div>
        </div>
        <div class="clear"></div>
        <div class="col-sm-5" id="js">
            <div class="box">
                <div class="error"></div>
                <div class="success"></div>
                <div id="<?= CMS::$Config['register']['avatar'] ?>" class="avatareditor">
                    <div class="types" id="nav">
                        <ul>
                            <li class="active"><a href="#" data-navigate="hd" data-subnav="gender"><img src="/Simple/Assets/img/avatar/body.png"/> </a> </li>
                            <li> <a href="#" data-navigate="hr" data-subnav="hair"><img src="/Simple/Assets/img/avatar/hair.png"/> </a> </li>
                            <li> <a href="#" data-navigate="ch" data-subnav="tops"><img src="/Simple/Assets/img/avatar/tops.png"/> </a> </li>
                            <li> <a href="#" data-navigate="lg" data-subnav="bottoms"><img src="/Simple/Assets/img/avatar/bottoms.png"/> </a> </li>
                        </ul>
                    </div>
                    <div class="types" id="nav">
                        <ul id="gender" class="display">
                            <li> <a href="#" id="M" data-gender="M">Mannelijk</a> </li>
                            <li> <a href="#" id="F" data-gender="F">Vrouwelijk</a> </li>
                        </ul>
                        <ul id="hair" class="hidden">
                            <li> <a href="#" class="hair" data-navigate="hr">Haar</a> </li>
                            <li> <a href="#" class="hats" data-navigate="ha">Mutsen</a> </li>
                            <li> <a href="#" class="hair-accessories" data-navigate="he">Hoofdtooi</a> </li>
                            <li> <a href="#" class="glasses" data-navigate="ea">Brillen</a> </li>
                            <li> <a href="#" class="moustaches" data-navigate="fa">Maskers</a> </li>
                        </ul>
                        <ul id="tops" class="hidden">
                            <li> <a href="#" class="tops" data-navigate="ch">Tops</a> </li>
                            <li> <a href="#" class="chest" data-navigate="cp">Sticker</a> </li>
                            <li> <a href="#" class="jackets" data-navigate="cc">Jassen</a> </li>
                            <li> <a href="#" class="accessories" data-navigate="ca">Juwelen</a> </li>
                        </ul>
                        <ul id="bottoms" class="hidden">
                            <li> <a href="#" class="bottoms" data-navigate="lg">Broeken</a> </li>
                            <li> <a href="#" class="shoes" data-navigate="sh">Schoenen</a> </li>
                            <li> <a href="#" class="belts" data-navigate="wa">Riemen</a> </li>
                        </ul>
                    </div>
                    <div style="float:right;right:5%;position:relative">
                        <img id="avatar" value="" src="">
                        <div style="clear: both"></div>
                        <input type="hidden" name="figure" id="avatar-code">
                    </div>
                    <div id="clothes-colors" style="margin-bottom:20px;margin-top:40px;margin-left:12px">
                        <div id="clothes"></div>
                        <div style="clear: both"></div>
                        <div id="palette"></div>
                    </div>
                </div>
                <div style="clear:both"></div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/profile/avatar.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/register.js"></script>
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
    <?php } ?>
<?php

?>