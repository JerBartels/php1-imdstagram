<?php

include_once("../classes/Db.class.php");
include_once("../classes/User.class.php");
include_once("session.php");
include_once("reglog.php");

//upload path
define('GW_UPLOADPATH', '../assets/');

//nieuwe user aanmaken
$user =  new User();
//userinfo ophalen o.b.v. sessie-username
$db_user = $user->getUserByUsername($_SESSION["username"]);
//alle gegevens opvullen o.b.v. opgehaalde userinfo
$user->Firstname = $db_user["firstname"];
$user->Lastname = $db_user["lastname"];
$user->Username = $db_user["username"];
$user->Email = $db_user["email"];
$user->Pass = $db_user["pass"];
$user->ProfilePic = $db_user["profilepic"];


?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Satchmo.cc</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700italic,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body>

<nav id="nav">
    <div id="nav_content">
        <a href="index.php" class="logo_app">logo</a>
        <a href="logout.php" class="logout_app">log out</a>
    </div>
</nav>

<div class="clearfix"></div>

<div class="container">
    <div id="profile">
        <div id="profile_content">
            <?php echo '<img src="../assets/' . $user->ProfilePic . '"alt="profile_pict" class="profile_pict">' ?>
            <h1><?php print $user->Firstname . " " . $user->Lastname?></h1>
            <a class="btn_a" href="profile.php">change profile</a>
        </div>
    </div>
    <div id="feed">
        <div id="feed_content"></div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="../scripts/script.js"></script>
</body>
</html>