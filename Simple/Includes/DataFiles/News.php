<?php

if ($_POST['action'] == 'loadArticles'  && isset($_POST['load']) && ($Load = (int)$_POST['load']) >= 0 && $Load < 10000)
{
    $Data['news'] = CMS::$MySql->query('SELECT id,slug,title FROM cms_news ORDER BY id DESC LIMIT ' . $Load * 30 . ',30');
    $Data['texts'] = CMS::$Lang['news'] . '|' . CMS::$Lang['next'] . '|' . CMS::$Lang['previous'];
    exit (json_encode($Data));
}

if ($_POST['action'] == "addReaction" && isset($_POST['article'], $_POST['reaction']) && ($Article = (int)$_POST['article']) > 0 && Users::$Session == true)
{
    if (!$News = CMS::$MySql->row('SELECT id,comments FROM cms_news WHERE id = :article', array('article' => $Article))) {
        $Data['message'] = CMS::$Lang['newsdoesnotexist'];
    } else if ($News['comments'] == 0) {
        $Data['message'] = CMS::$Lang['newsreactionsdisabled'];
    } else if (strlen($_POST['reaction']) < 5 || strlen($_POST['reaction']) > 100) {
        $Data['message'] = CMS::$Lang['invalidreaction'];
    } else if (($Last = CMS::$MySql->single('SELECT date FROM cms_news_reactions WHERE userid = :userid ORDER BY date DESC LIMIT 1', array('userid' => Users::$Session->ID))) > time() - CMS::$Config['cms']['timebetweennewspost'] * 60) {
        $Time = Site::secondsToTime($Last - time() + CMS::$Config['cms']['timebetweennewspost'] * 60);
        $Data['message'] = str_ireplace('%minutes%', $Time['m'], CMS::$Lang['reactionwait']);
    } else if (Site::BadWord($_POST['reaction'])) {
        $Data['message'] = CMS::$Lang['blockedreaction'];
    } else {
        $Data['valid'] = true;
        $Data['message'] = CMS::$Lang['reactionplaced'];
        CMS::$MySql->query('INSERT INTO cms_news_reactions (newsid, userid, date, message) VALUES(:article, :userid, :date, :reaction)', array('article' => $Article, 'userid' => Users::$Session->ID, 'date' => time(), 'reaction' => htmlspecialchars($_POST['reaction'])));
    }
    exit (json_encode($Data));
}

if ($_POST['action'] == "loadReactions" && isset($_POST['article'], $_POST['load']) && ($Load = (int)$_POST['load']) >= 0 && $Load < 10000)
{
    if ($Reactions = CMS::$MySql->query('SELECT cms_news_reactions.id,message,date,deleted,users.username,users.look,users.online FROM cms_news_reactions LEFT JOIN users ON users.id = userid WHERE newsid = :article ORDER BY id ASC LIMIT '.$Load * 10 .',10', array('article' => $_POST['article'])))
    {
        $i = 0;
        foreach ($Reactions as $Row)
        {
            $Data['reaction'][$i]['id'] = $Row['id'];
            $Data['reaction'][$i]['message'] = $Row['deleted'] ? CMS::$Lang['newsreactiondeleted'] : $Row['message'];
            $Data['reaction'][$i]['writtenby'] = str_ireplace(array('%username%','%date%'), array($Row['username'],date('d-m-Y H:i:s', $Row['date'])), CMS::$Lang['newsreactionwrittenby']);
            $Data['reaction'][$i]['look'] = $Row['look'];
            $Data['reaction'][$i]['online'] = $Row['online'];
            $i++;
        }
        exit (json_encode($Data));
    }
    return;
}