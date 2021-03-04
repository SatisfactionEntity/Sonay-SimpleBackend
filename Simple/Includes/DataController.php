<?php

if (isset($_POST['action'], $_POST['controller']))
{
    if (count($_POST) > 15)
        exit ('Too many POST requests!');

    foreach ($_POST as $Check) {
        if (is_array($Check))
            exit ('POST request blocked!');
    }

    if (!ctype_alnum($_POST['controller']) || !file_exists('Simple/Includes/DataFiles/' . $_POST['controller'] . '.php'))
        exit ('Controller not found!');
    else
        require_once('Simple/Includes/DataFiles/' . $_POST['controller'] . '.php');

    echo 'Action not found or access denied!';
}
