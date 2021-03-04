<?php
$this->WriteInc('Header');
?>
    <script>
        var SimpleCMS = {
            avatar: '<?= CMS::$Config['cms']['avatarlocation'] ?>'
        }
    </script>
    <style>
        p {
            font-size: 13px;
        }
    </style>
    <div class="row">
        <div class="col-sm-4">
            <div class="box">
                <h2 class="orange">Laatste nieuws</h2>
                    <?php
                    if (!$News = CMS::$Cache->get('newslist')) {
                        $News = CMS::$MySql->query('SELECT id,title,slug,published FROM cms_news ORDER by id DESC LIMIT 30');
                        CMS::$Cache->set('newslist', $News ? $News : $News = 'none');
                    }
                    if (is_array($News)) {
                        $Data = '<div id="article-archive">';
                        $number = 8;
                        foreach ($News as $Row) {
                            if ($Row['published'] > time() - 86400) {
                                if ($number != 1) {
                                    $number = 1;
                                    $Data .= '<h2>'.CMS::$Lang['today'].'</h2><ul>';
                                }
                            } else if ($Row['published'] > time() - 172800) {
                                if ($number < 2) {
                                    $Data .= '</ul>';
                                }
                                if ($number != 2) {
                                    $number = 2;
                                    $Data .= '<h2>'.CMS::$Lang['yesterday'].'</h2><ul>';
                                }
                            } else if ($Row['published'] > time() - 604800) {
                                if ($number < 3) {
                                    $Data .= '</ul>';
                                }
                                if ($number != 3) {
                                    $number = 3;
                                    $Data .= '<h2>'.CMS::$Lang['thisweek'].'</h2><ul>';
                                }
                            } else if ($Row['published'] > time() - 1209600) {
                                if ($number < 4) {
                                    $Data .= '</ul>';
                                }
                                if ($number != 4) {
                                    $number = 4;
                                    $Data .= '<h2>'.CMS::$Lang['lastweek'].'</h2><ul>';
                                }
                            } else if ($Row['published'] > time() - 2592000) {
                                if ($number < 5) {
                                    $Data .= '</ul>';
                                }
                                if ($number != 5) {
                                    $number = 5;
                                    $Data .= '<h2>'.CMS::$Lang['thismonth'].'</h2><ul>';
                                }
                            } else if ($Row['published'] > time() - 5184000) {
                                if ($number < 6) {
                                    $Data .= '</ul>';
                                }
                                if ($number != 6) {
                                    $number = 6;
                                    $Data .= '<h2>'.CMS::$Lang['lastmonth'].'</h2><ul>';
                                }
                            } else {
                                if ($number < 7) {
                                    $Data .= '</ul>';
                                }
                                if ($number != 7) {
                                    $number = 7;
                                    $Data .= '<h2>'.CMS::$Lang['other'].'</h2><ul>';
                                }
                            }
                            $Data .= '<li><a href="/news/' . $Row['id'] . '/' . $Row['slug'] . '">' . $Row['title'] . ' &raquo;' . '</a></li>';
                        }
                        $Data .= '</ul>';
                        if (count($News) >= 30) {
                           $Data .= '<span style="float:right" data-id="0" id="more">'.CMS::$Lang['morenews'].' »</span><div style="clear:both"></div>';
                        }
                        $Data .= '</div>';
                    }
                    else {
                        $Data = '<div class="inner">Geen nieuws gevonden!</div>';
                    }
                    echo $Data;
                    ?>
            </div>
        </div>
        <div class="clear"></div>
        <div class="col-sm-8">
            <div class="box">
                <?php
                $Url = isset(CMS::$Router->Request->SubUrls[0]) ? (int)substr(CMS::$Router->Request->SubUrls[0], 1) : 0;
                if ($Url === 0) {
                    $Row = CMS::$MySql->row('SELECT cms_news.id,title,shortstory,longstory,published,users.username,permissions.rank_name,comments FROM cms_news LEFT JOIN users ON users.id = author LEFT JOIN permissions ON permissions.id = users.rank ORDER by id DESC LIMIT 1');
                } else {
                    $Row = CMS::$MySql->row('SELECT cms_news.id,title,shortstory,longstory,published,users.username,permissions.rank_name,comments FROM cms_news LEFT JOIN users ON users.id = author LEFT JOIN permissions ON permissions.id = users.rank WHERE cms_news.id = :article', array('article' => $Url));
                }
                if ($Row) {
                    echo '<h2 id="title" data-id="' . $Row['id'] . '" class="blue">' . $Row['title'] . '</h2><div id="article-wrapper"><div class="article-meta">Gepost op: ' . date('F j, Y', $Row['published']) . '</div><p class="summary">' . $Row['shortstory'] . '</p><div class="article-body">' . $Row['longstory'] . '</div><img src="/Simple/Assets/img/hotel.gif" style="margin: 0px 10px 10px 0px;float:left"><strong style="line-height:normal"><span style="color: rgb(0, 0, 0); font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 11px;">'.$Row['username'].', '.$Row['rank_name'].'</span></strong></div></div>';
                    if ($Row['comments'] == 1 && Users::$Session == true) {
                        echo '<div class="box"><h2 class="blue">Plaats reactie</h2><div class="inner"><div class="error"></div><div class="success"></div><textarea class="input" id="give-comment"></textarea><br><br><button class="sendcomment btn btn-success btn-block">Plaats reactie</button></div></div>';
                    }
                    if ($Reactions['reactions'] = CMS::$MySql->query('SELECT cms_news_reactions.id,message,date,deleted,users.username,users.look,users.online FROM cms_news_reactions LEFT JOIN users ON users.id = userid WHERE newsid = :article ORDER BY id ASC LIMIT 10', array('article' => $Row['id']))) {
                        $Reactions['tabs'] = ceil(CMS::$MySql->single('SELECT COUNT(*) FROM cms_news_reactions WHERE newsid = :article', array('article' => $Row['id'])) / 10);
                    }
                    if ($Reactions['reactions']) {
                        if ($Reactions['tabs'] > 1) {
                            echo '<div class="class-list right">';
                            for ($i = 1; $i <= $Reactions['tabs']; $i++) {
                                echo '<div data-id="' . $i . '" class="class-1">' . $i . '</div>';
                            }
                            echo '</div><div style="clear:both"></div>';
                        }
                        echo '<div class="box" style="padding:0"><div id="column" class="right newsreactions"><div class="guestbook scroll" style="width:auto;border-radius:5px">';
                        $i = 0;
                        foreach ($Reactions['reactions'] as $Message)
                        {
                            echo '<div data-id="'.$Message['id'].'" class="reaction '.($i % 2 == 0 ? 'odd' : 'even').'"><img class="duck" src="/Simple/Assets/img/duck_'.($Message['online'] == '1' ? 'online' : 'offline').'.gif"><div class="plate"><img src="'.CMS::$Config['cms']['avatarlocation'].'?figure='.$Message['look'].'&direction=2&head_direction=3&action=wlk&gesture=sml" style="-webkit-filter: drop-shadow(0 1px 0 #FFFFFF) drop-shadow(0 -1px 0 #FFFFFF) drop-shadow(1px 0 0 #FFFFFF) drop-shadow(-1px 0 0 #FFFFFF);margin-top:-20px;margin-left:25px"></div><div class="message">'.($Message['deleted'] ? CMS::$Lang['newsreactiondeleted'] : $Message['message']).'</div><div class="timestamp">'.str_ireplace(array('%username%','%date%'), array($Message['username'],date('d-m-Y H:i:s', $Message['date'])), CMS::$Lang['newsreactionwrittenby']).'</div></div>';
                            $i++;
                        }
                        echo '</div></div></div>';
                    }
                } else {
                    echo '<h2 id="title" class="red">Artikel niet gevonden!</h2><div class="inner">Oeps dit nieuws artikel kan niet worden gevonden!</div>';
                } ?>
        </div>
    </div>
    <script type="text/javascript" src="/Simple/Assets/js/global/script.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/loaders/news.js"></script>
<?php
$this->WriteInc('Footer');
?>
