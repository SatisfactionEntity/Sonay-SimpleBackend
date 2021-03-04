<?php
$this->WriteInc('Header');
?>
    <div class="row">
        <div class="col-md-12">
            <ol class="breadcrumb box">
                <div class="mid">
                </div>
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-md-8">
            <div class="box badges">
                <h2 class="blue">Laden...</h2>
                <div class="inner">Bezig met laden van badges...</div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="box showitems">
                <div class="success"></div>
                <div class="error"></div>
                <div class="row">
                    <div class="col-md-8">
                        <ul id="items">
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                            <li></li>
                        </ul>
                    </div>
                    <div class="col-md-4">
                        <p class="costs">Kost: <br><b class="price">0</b> <img src="/Simple/Assets/img/5.gif"></p>
                        <p class="costs">Korting: <br><b><span class="discount">0</span>%</b></p>
                        <p class="costs">Totale prijs: <br><b class="total">0</b> <img src="/Simple/Assets/img/5.gif">
                        </p>
                        <p class="costs">Je hebt: <br><b class="have"><?= Users::$Session->Data['5'] ?></b> <img
                                    src="/Simple/Assets/img/5.gif"></p>
                    </div>
                    <div class="col-md-12">
                        <br>
                        <button id="0" class="btn btn-success buy" style="width:100%;">Badges kopen</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/badges.js?2"></script>
<?php
$this->WriteInc('Footer');
?>