<?php
$this->WriteInc('Header');
?>
    <link rel="stylesheet" href="/Simple/Assets/css/values/style.css">
    <div class="row">
        <div class="col-sm-3">
            <div class="box">
                <h2 class="blue">Categorieën</h2>
                <div id="credits-safety" class="box-content credits-info">
                    <div class="credit-info-text clearfix">
                        <div class="options blue">
                            <ul style="margin-top:5px">
                                <?php
                                $Pages = CMS::$MySql->query('SELECT id,name FROM cms_values_categories ORDER by order_num ASC');
                                if ($Pages) {
                                    foreach ($Pages as $Row) {
                                        echo '<li data-id="'.$Row['id'].'">'.$Row['name'].'</li>';
                                    }
                                }
                                else {
                                    echo CMS::$Lang['nocategoriesvaluesfound'];
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-9">
        </div>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/values.js"></script>
<?php
$this->WriteInc('Footer');
?>