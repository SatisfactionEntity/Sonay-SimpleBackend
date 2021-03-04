<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="https://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= CMS::$Config['cms']['hotelname'] ?>: <?php echo $this->Title ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link href="/Simple/Assets/manage/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="/Simple/Assets/manage/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
    <link href="http://fonts.googleapis.com/css?family=Lato" rel="stylesheet" type="text/css">
    <link href="/Simple/Assets/manage/css/style.css" rel="stylesheet" type="text/css"/>
    <link rel="shortcut icon" href="/Simple/Assets/img/favicon.ico" type="image/vnd.microsoft.icon"/>
    <script type="text/javascript" src="/Simple/Assets/js/global/jquery.min.js"></script>
    <script type="text/javascript" src="/Simple/Assets/js/global/jquery-ui.min.js"></script>
    <script type="text/javascript" src="/Simple/Assets/manage/js/script.js"></script>
</head>
<header style="    padding-top: 20px;" class="header">
    <div class="logo">
        ADMIN PANEL
    </div>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="navbar-btn sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </a>
        <div class="navbar-right">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <i class="fa fa-user"></i>
                        <span> <i class="caret"></i></span>
                    </a>
                    <ul class="dropdown-menu dropdown-custom dropdown-menu-right">
                        <li>
                            <a href="/me"><i class></i>Homepage</a>
                            <a href="/staff"><i class></i>Staff Page</a>
                            <a onclick="logout()" href="#"><i class="fa fa-ban fa-fw pull-right"></i>Logout</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
<!DOCTYPE html>
<body style="margin-top: -20px;" class="skin-black">
<div class="wrapper row-offcanvas row-offcanvas-left">
    <aside class="left-side sidebar-offcanvas">
        <section class="sidebar">
            <ul class="sidebar-menu">
                <?php
                $icons = Array (
                        'dashboard' => 'fa-dashboard',
                        'news' => 'fa-newspaper-o',
                        'wordfilter' => 'fa-filter',
                        'users' => 'fa-user',
			            'ban' => 'fa-ban',
                        'values' => 'fa-diamond',
                        'filter' => 'fa-filter',
						'chatlogs' => 'fa-filter'		
                );
                foreach (CMS::$Config['manage'] as $name => $action) {
                    if (Users::$Session->HasPermission($action)) {
                        echo '<li><a href="/manage/'.$name.'"><i class="fa '.$icons[$name].'""></i> <span>'.CMS::$Lang[$name].'</span></a></li>';
                    }
                }
                ?>
            </ul>
        </section>
    </aside>
    <aside class="right-side">
        <section class="content">
