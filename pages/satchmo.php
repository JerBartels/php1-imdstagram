<?php

include_once("classes/Db.class.php");
include_once("classes/User.class.php");
include_once("session.php");

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>This is Satchmo.cc</title>
</head>
<body>

<h1>SATCHMO.CC</h1>
<h2><?php echo "Booeyaaaaaaa! Happy to see you, " . $_SESSION["username"] . "!"; ?></h2>

<a href="logout.php">Log out</a>

</body>
</html>