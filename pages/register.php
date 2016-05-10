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
                    <input type="text" name="username" id="username" placeholder="username" value="<?php if(isset($_POST['username'])){ print $_POST['username']; } ?>">
                    <?php if(isset($_POST['username']) && !validateUsername($_POST['username'])){echo $err_username;} ?>
                    <input type="text" name="firstname" id="firstname" placeholder="firstname" value="<?php if(isset($_POST['firstname'])){ print $_POST['firstname']; } ?>">
                    <?php if(isset($_POST['firstname']) && !validateFirstname($_POST['firstname'])){echo $err_firstname;} ?>
                    <input type="text" name="lastname" id="lastname" placeholder="lastname" value="<?php if(isset($_POST['lastname'])){ print $_POST['lastname']; } ?>">
                    <?php if(isset($_POST['lastname']) && !validateLastname($_POST['lastname'])){echo $err_lastname;} ?>
                    <input type="text" name="email" id="email" placeholder="your@email" value="<?php if(isset($_POST['email'])){ print $_POST['email']; } ?>">
                    <?php if(isset($_POST['email']) && !validateEmail($_POST['email'])){echo $err_email;} ?>
                    <input type="password" name="pass" id="pass" placeholder="password" value="<?php if(isset($_POST['pass'])){ print $_POST['pass']; } ?>">
                    <?php if(isset($_POST['pass']) && !validatePass($_POST['pass'])){echo $err_pass;} ?>
                    <input type="submit" name="signup" id="signup" value=">" class="btn_round">
                </form>

                <p class="form_error"><?php echo $feedback ?></p>
            </div>

        </div>
    </div>
</div>


<div class="btn_bottom">
    <a class="btn_a_bottom" href="../index.php">home</a>
</div>


</body>
</html>