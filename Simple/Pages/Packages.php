<?php
$this->WriteInc('Header');
?>
<script>
    var SimpleCMS = {
        icon: '<?= CMS::$Config['cms']['furniiconmap'] ?>',
        badge: '<?= CMS::$Config['cms']['badgemap'] ?>'
    }
</script>
<div class="row">
    <div class="col-md-8">
        <div class="box present-list">
            <div style="text-align:center" class="error"></div>
            <div style="text-align:center" class="success"></div>
            <table id="packages" style="width:100%">
                <?php
                if (!$Packages = CMS::$Cache->get('packages')) {
                    $Packages = CMS::$MySql->query('SELECT `id`,`title`,`desc`,`image`,`content`,`currency_type`,`price` FROM cms_packages WHERE enabled = 1');
                    CMS::$Cache->set('packages', $Packages ? $Packages : 'none');
                }
                if (is_array($Packages))
                {
                    foreach ($Packages as $Row)
                    {
                        echo '<tr><td class="price"><div class="present" id="'.$Row['id'].'" data-content="'.$Row['content'].'" data-image="'.$Row['image'].'" data-currency_type="'.$Row['currency_type'].'" data-price="'.$Row['price'].'"><img src="/Simple/Assets/img/'.$Row['image'].'"</div></td><td class="second"><p class="title">'.$Row['title'].'</p><p class="desc">'.$Row['desc'].'</p></td><td class="button"><button id="'.$Row['id'].'" class="show btn btn-success">'.CMS::$Lang['show'].'</button></td></tr>';
                    }
                }
                else
                {
                    echo CMS::$Lang['nopackages'];
                }
                ?>
            </table>
        </div>
    </div>
    <div class="col-md-4">
        <div class="box showitems">
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
                <div class="col-md-4 present_info">
                    <p class="costs">Kost: <br><span class="price"><b>xxxxx</b></span></p>
                    <p class="costs">Je hebt: <br><span class="have" data-0="<?= Users::$Session->Data['0'] ?>"
                                                        data-5="<?= Users::$Session->Data['5'] ?>"
                                                        data-103="<?= Users::$Session->Data['103'] ?>"><b>xxxxx</b></span>
                    </p>
                    <div class="present"></div>
                </div>
                <div class="col-md-12">
                    <br>
                    <button id="0" class="btn btn-success buy" style="width:100%;">Kopen</button>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
<script type="text/javascript" src="/Simple/Assets/js/loaders/packages.js"></script>
<?php
$this->WriteInc('Footer');
?>
