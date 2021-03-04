<?php

if ($_POST['action'] == "addTag" && isset($_POST['tag']) && Users::$Session == true)
{
    if (CMS::$MySql->single('SELECT COUNT(*) FROM cms_tags WHERE userid = :userid', array('userid' => Users::$Session->ID)) > CMS::$Config['cms']['maxtags']) {
        $Data['error'] = str_ireplace("%amount%", CMS::$Config['cms']['maxtags'], CMS::$Lang['maxtagsreached']);
    } else if (CMS::$MySql->single("SELECT COUNT(*) FROM cms_tags WHERE userid = :userid AND tag = :tag", array('userid' => Users::$Session->ID, 'tag' => $_POST['tag'])) >= 1) {
        $Data['error'] = CMS::$Lang['tagalreadyadded'];
    } else if (!Users::ValidTag($_POST['tag']) || Site::BadWord($_POST['tag'], false)) {
        $Data['error'] = CMS::$Lang['taginvalid'];
    } else {
        CMS::$MySql->query('INSERT INTO cms_tags (userid, tag) VALUES(:userid, :tag)', array('userid' => Users::$Session->ID, 'tag' => strtolower($_POST['tag'])));
        $Data['valid'] = true;
    }
    $Data['message'] = CMS::$Lang['taghelpbefore'] . CMS::$Lang['taghelp'.rand(1, 14)];
    exit (json_encode($Data));
}

if ($_POST['action'] == "deleteTag" && isset($_POST['tag']) && Users::$Session == true)
{
    CMS::$MySql->query("DELETE FROM cms_tags WHERE userid = :userid AND tag = :tagid", array('userid' => Users::$Session->ID, 'tagid' => $_POST['tag']));
    $Data['valid'] = true;
    exit (json_encode($Data));
}