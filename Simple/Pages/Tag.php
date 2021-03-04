<?php
$this->WriteInc('Header');
?>
<script>
    var SimpleCMS = {
        maxtags: '<?= CMS::$Config['cms']['maxtags'] ?>'
    }
</script>
<div class="row">
    <div class="col-md-4">
        <div class="box">
            <h2 class="red">Populaire tags</h2>
            <div id="tags">
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
            </div>
        </div>
        <?php if (Users::$Session == true) { $MyTags = CMS::$MySql->column('SELECT tag FROM cms_tags WHERE userid = :userid', array('userid' => Users::$Session->ID)); ?>
            <div class="box">
                <h2 class="green">Mijn tags</h2>
                <div class="panel-body panel-mytags">
                    <div class="error TagError" style="display:none"></div>
                    <div<?= (count((array)$MyTags) >= CMS::$Config['cms']['maxtags'] ? ' style="display:none"' : '') ?>>Je hebt nog veel meer plek voor tags. Voeg er nog een paar toe!</div>
                    <div<?= (count((array)$MyTags) < CMS::$Config['cms']['maxtags'] ? ' style="display:none"' : '') ?>>Je hebt het maximaal aantal tags bereikt. Wis een van je interesses om een nieuwe toe te voegen.</div>
                    <hr>
                    <ul class="tag-list make-clickable" style="padding:0px">
                        <?php
                        if ($MyTags) {
                            foreach ($MyTags as $MyTag) {
                                echo '<li class="tag" data-identifier="'.$MyTag.'"><a href="/tag/'.$MyTag.'" class="tag" style="font-size: 10px;">'.$MyTag.'</a><a class="tag-remove-link" data-name="'.$MyTag.'"></a></li>';
                            }
                        } ?>
                    </ul>
                    <div id="addtagpart"<?= (count((array)$MyTags) >= CMS::$Config['cms']['maxtags'] ? ' style="display:none"' : '') ?>>
                        <div class="form-group">
                            <input class="tag-name form-control" name="tag">
                        </div>
                        <div class="form-group">
                            <button id="addtag" class="btn btn-success">Voeg tag toe</button>
                        </div>
                        <div class="taghelp">
                            <center>
                                <em><?= CMS::$Lang['taghelpbefore'] . CMS::$Lang['taghelp'.rand(1, 14)] ?></em>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-md-8">
        <div class="box staff">
            <?php
                $Tag = strtolower(isset(CMS::$Router->Request->SubUrls[0]) && ctype_alnum($Request = substr(CMS::$Router->Request->SubUrls[0], 1)) && strlen($Request) < 20 ? $Request : CMS::$Config['cms']['hotelname']);
                $Load = isset(CMS::$Router->Request->SubUrls[1]) && ($Load = (int)substr(CMS::$Router->Request->SubUrls[1], 1)) > 0 && $Load < 10000 ? $Load : 1;
                if ($Tags['player'] = CMS::$MySql->query('SELECT username,motto,look,GROUP_CONCAT(t1.tag ORDER BY t1.id ASC) AS tags FROM cms_tags LEFT JOIN users ON users.id = cms_tags.userid LEFT JOIN cms_tags t1 ON t1.userid = cms_tags.userid WHERE cms_tags.tag = :tag GROUP BY cms_tags.id ORDER BY cms_tags.id DESC LIMIT '. ($Load-1) * 10 . ',10', array('tag' => $Tag))) {
                    $Tags['count'] = CMS::$MySql->single('SELECT COUNT(*) FROM cms_tags WHERE tag = :tag', array('tag' => $Tag));
                }
            ?>
            <h2 id="tagname" class="blue">Zoek nieuwe vrienden</h2>
            <div class="panel-tags" style="overflow: hidden">
                <div style="float: left;padding: 8px 8px 8px 0;width: 100%;">
                    <div class="col-md-6 no-padding">
                        <div class="input-group">
                            <input type="text" class="form-control" id="search" data-current="<?= $Tag ?>" value="<?= $Tag ?>" placeholder="Tags zoeken..."
                                   required>
                            <span class="input-group-btn">
                                <button class="btn btn-secondary searchTag"><i class="fa fa-search" aria-hidden="true"></i></button>
                            </span>
                        </div>
                    </div>
                    <p id="numbers" style="float:right;margin-top:7px;"><?= ($Tags['player'] ? ($Load == 1 ? 1 : ($Load * 10) + 1 - 10) . ' - ' . ($Load * 10 > $Tags['count'] ? $Tags['count'] : $Load * 10) . ' / ' . $Tags['count'] : 'Niets gevonden.')?></p>
                </div>
                <?php
                if (Users::$Session == true && (!$MyTags || !isset(array_flip($MyTags)[$Tag])) && count((array)$MyTags) < CMS::$Config['cms']['maxtags']) {
                    echo '<div id="tagadd" style="float:left;padding:8px">Tag jezelf met: <button class="btn btn-secondary searchTag">'.$Tag.'</button><p></p></div>';
                }
                if ($Tags['player']) {
                    echo '<table class="table table-striped table-hover"><tbody style="font-size:14px">';
                    foreach ($Tags['player'] as $Player) {
                        echo '<tr><td style="width:75px"><img src="'.CMS::$Config['cms']['avatarlocation'].'?figure=' . $Player['look'] . '&head_direction=3&headonly=true&gesture=sml"></td><td class="tag-list" style="text-align:left"><a href="/home/' . $Player['username'] . '">' . $Player['username'] . '</a><br>' . htmlspecialchars($Player['motto']) . '<br>';
                        foreach (explode(',', $Player['tags']) as $list) {
                            echo '<li class="utag"><a href="/tag/' . $list . '">' . $list . '</a></li>';
                        }
                        echo '</tr>';
                    }
                    echo '</tbody></table>';
                    if ($Tags['count'] > 10) {
                        echo '<p class="search-result-navigation" style="text-align:center">' . ($Load == 1 ? '<strong>Eerste</strong> ' : '<a href="/tag/' . $Tag . '/1">Eerste</a> ');
                        $Last = Ceil($Tags['count'] / 10);
                        for ($i = 1; $i <= $Last; $i++) {
                            echo $i == $Load ? '<strong>' . $i . ' </strong> ' : '<a href="/tag/' . $Tag . '/' . $i . '">' . $i . '</a> ';
                        }
                        echo $Load == $Last ? '<strong>Laatste</strong>' : '<a href="/tag/' . $Tag . '/' . $Last . '">Laatste</a></p>';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
<script type="text/javascript" src="/Simple/Assets/js/loaders/tag.js"></script>
<?php
$this->WriteInc('Footer');
?>
