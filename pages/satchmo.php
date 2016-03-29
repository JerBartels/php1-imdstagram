<?php

include_once("../classes/Db.class.php");
include_once("../classes/User.class.php");
include_once("ajax.php");
include_once("session.php");

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

?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Satchmo.cc</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body>
    <nav>
        <a href=""><?php echo $_SESSION["username"] ?></a>
        <a href="logout.php">Log out</a>
    </nav>

    <div id="feed">
        <h1>SATCHMO.CC</h1>
        <h2><?php echo "Booeyaaaaaaa! Happy to see you, " . $_SESSION["username"] . "!"; ?></h2>
    </div>

    <div id="profile">
        <h2>this is you!</h2>
        <form action="" method="post">
            <input type="text" readonly value="<?php print $user->Username; ?>">
            <input type="text" readonly value="<?php print $user->Firstname; ?>">
            <input type="text" readonly value="<?php print $user->Lastname; ?>">
            <input type="text" readonly value="<?php print $user->Email; ?>">
            <input type="password" readonly value="<?php print $user->Pass; ?>">
            <input type="submit" class="button" name="change" value="change" />
            <input type="submit" class="button" name="save" value="save" />
        </form>
        <p id="profile_feedback"> <?php echo $profile_feedback ?></p>
    </div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="../scripts/script.js"></script>
</body>
</html>