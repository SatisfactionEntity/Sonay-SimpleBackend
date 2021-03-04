<?php
$this->WriteInc('Header');
?>
<div class="row">
    <div class="col-sm-6">
        <div class="box">
            <h2 class="green">Wachtwoord opvragen</h2>
            <div class="inner">
                <?php
                if (!isset(CMS::$Router->Request->SubUrls[0]) || !$Row = CMS::$MySql->row('SELECT userid AS id,expire,users.username,users.mail FROM cms_forgot_password LEFT JOIN users ON users.id = userid WHERE code = :code', array('code' => substr(CMS::$Router->Request->SubUrls[0], 1)))) {
                    echo CMS::$Lang['mailforgotinvalidcode'];
                }
                else if ($Row['expire'] < time()) {
                    echo CMS::$Lang['mailforgotexpired'];
                }
                else {
                    echo Site::SendMail('NewPassword', $Row)['response'];
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
<?php
$this->WriteInc('Footer');
?>
