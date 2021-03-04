<script type="text/javascript" src="/Simple/Assets/js/global/clipboard.min.js"></script>
<link rel="stylesheet" href="/Simple/Assets/css/Sonay/style.css?<?= time() ?>">

    <div class="row">

        <div class="col-14">
            <div id="shadow-box" style="max-height:100%; align-content: space-around;">
                <div class="title-box png20" style="background-color:#00796B;background-image:url(/Simple/Assets/img/devbox.png);background-repeat: no-repeat;background-position: right;background-size: cover;height: 80px;">
                    <div class="title2"><font color="white"><?= CMS::$Config['cms']['hotelname'] ?> Foto's</font></div>
                    <div class="desc2"><font color="white">Alle mooie <?= CMS::$Config['cms']['hotelname'] ?>'s foto's</font></div>
                </div>
                <div class="png20 stataantal">
                    <?php
                    if (!$Photos = CMS::$Cache->get('web_photos')) {
                        $Photos = CMS::$MySql->column("SELECT url FROM camera_web GROUP BY url ORDER BY id DESC LIMIT " .CMS::$Config['cms']['maxwebphotos']);
                    }
                    if ($Photos) {

                        echo '<ul class="tag-list">';
                        foreach ($Photos as $Photo) {

                            echo '<li><br><img src="' . $Photo . ' " alt="Girl in a jacket" width="235px;"></li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '<div class="inner">Geen Fotos gevonden.</div>';
                    } ?>
                    <p></p>					</div>
            </div>

        </div>

    </div>
</div>

