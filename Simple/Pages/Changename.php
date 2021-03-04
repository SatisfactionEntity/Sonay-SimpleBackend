<?php
$this->WriteInc('Header');
?>
    <div class="row">
        <div class="col-sm-8">
            <div class="box">
                <h2 class="dark-blue">Naam veranderen</h2>
                <div class="inner">
                    <div class="error"></div>
                    <div class="success"></div>
                    <div class="message"><?= str_ireplace("%amount%", CMS::$Config['cms']['namechangecost'], CMS::$Lang['namechangetext']) ?></div>
                    <div class="data"><br><br><b class="question">Nieuwe naam</b><br>Vul hier je nieuwe naam in.<input style=" margin-top:5px;" class="password" id="username"><b class="question">Wachtwoord</b><br>Vul hier je wachtwoord in.<input type="password" style="margin-top:5px;" class="password" id="password"> <button id="changename" style="float:right;" class="btn btn-primary">Opslaan</button></div>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
        <div class="col-sm-4">
            <div class="box">
                <h2 class="green">Jouw portomonee</h2>
                <div id="purse-habblet">
                    <ul style="margin: 0;">
                        <li class="even icon-purse-dia">
                            <div>Je hebt nu:</div>
                            <span class="my-currency"><span class="diamonds"><?= Users::$Session->Data['5'] ?></span> Diamanten</span>
                        </li>
                    </ul>
                    <div id="purse-redeem-result"></div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/changename.js"></script>
<?php
$this->WriteInc('Footer');
?>