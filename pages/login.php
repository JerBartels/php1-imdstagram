<?php

include_once("../classes/Db.class.php");
include_once("../classes/User.class.php");
include_once("session.php");
include_once("reglog.php");

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>satchmo.cc</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/index.css">
</head>

<body>

<div class="container">

    <div class="content-table">

        <div class="content-cell">

            <div class="header">
                <h1><a class="a_logo" href="../index.php">satchmo cc</a></h1>
            </div>

            <div class="my_form">
                <form action="" method="post" autocomplete="off">
                    <input type="text" name="l_username" id="l_username" placeholder="username" value="<?php if(isset($_POST['l_username'])){ print $_POST['l_username']; } ?>">
                    <?php if(isset($_POST['l_username']) && !validateUsername($_POST['l_username'])){echo $err_username;} ?>
                    <input type="password" name="l_pass" id="l_pass" placeholder="******" value="<?php if(isset($_POST['l_pass'])){ print $_POST['l_pass']; } ?>">
                    <?php if(isset($_POST['l_pass']) && !validatePass($_POST['l_pass'])){echo $err_pass;} ?>
                    <input type="submit" name="signin" id="signin" value=">" class="btn_round">
                </form>
                <p class="form_error"><?php echo $l_feedback ?></p>
            </div>

        </div>
    </div>

</div>

</body>
</html>