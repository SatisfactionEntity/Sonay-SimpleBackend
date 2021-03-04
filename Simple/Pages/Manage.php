<?php

if (isset(CMS::$Router->Request->SubUrls[0]) && isset(CMS::$Config['manage'][($Request = substr(CMS::$Router->Request->SubUrls[0], 1))]) && Users::$Session->HasPermission(CMS::$Config['manage'][$Request]))
{
    $this->WriteInc('AdminHeader');
        include('Simple/Includes/Admin/' . ucfirst($Request) . '.php');
    $this->WriteInc('AdminFooter');
}
else {
    Site::Stop('/index');
}


