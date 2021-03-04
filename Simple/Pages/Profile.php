<?php
$this->WriteInc('Header');
?>
    <script>
        var SimpleCMS = {
            avatar: '<?= CMS::$Config['cms']['avatarlocation'] ?>'
        }
    </script>
    <link rel="stylesheet" href="/Simple/Assets/css/profile/pure.css">
    <link rel="stylesheet" href="/Simple/Assets/css/profile/switchery.min.css"/>
    <div class="row">
        <div class="col-sm-4">
            <div class="box">
                <div class="options blue">
                    <ul>
                        <li id="Dashboard" class="active">Dashboard</li>
                        <hr class="optionhr">
                        <li id="Hotel">Hotel instellingen</li>
                        <li id="General">Algemene instellingen</li>
                        <li id="Email">Email instellingen</li>
                        <li id="Password">Wachtwoord instellingen</li>
                        <li id="Security">Beveiligings instellingen</li>
                        <li id="Look">Look aanpassen</li>
                        <hr class="optionhr">
                        <li id="Sessions">Sessie beheer</li>
                        <li id="Logs">Account activiteit</li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="clear"></div>
        <div class="col-sm-8" id="js">
            <div class="box">
                <div class="inner">
                    <h1><?= CMS::$Lang['dashboard'] ?></h1>
                    <img class="frank-key" src="/Simple/Assets/img/frank_12.gif">
                    <?= str_ireplace("%hotelname%", CMS::$Config['cms']['hotelname'], CMS::$Lang['dashboard2']) ?>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
    <script src="/Simple/Assets/js/profile/switchery.min.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/profile.js"></script>
<?php
$this->WriteInc('Footer');
?>