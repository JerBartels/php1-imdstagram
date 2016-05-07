<?php

include_once("classes/Db.class.php");
include_once("classes/User.class.php");
include_once("pages/session.php");
include_once("pages/reglog.php");

require_once __DIR__ . '/Facebook/autoload.php';

//kijken of user al ingelogd is
alreadyLoggedIn();

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>satchmo.cc</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="styles/reset.css">
    <link rel="stylesheet" href="styles/index.css">
</head>

<body>

<div class="container">

    <div class="content-table">

        <div class="content-cell">

            <div class="header">
                <h1><a class="a_logo" href="#">satchmo cc</a></h1>
            </div>

            <div class="menu">
                <a class="btn_a" href="pages/register.php">register</a>
                <a class="btn_a" href="pages/login.php">login</a>
                <a class="btn_a" href="pages/fb-login.php">facebook login</a>
            </div>

        </div>
    </div>

</div>

</body>
</html>

