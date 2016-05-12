<?php

include_once "../pages/init.php";
include_once "../pages/reglog.php";

$user = new User();

if($user->Exists($_POST["username"], "username"))
{
    echo 0;
}
else
{
    echo 1;
}

