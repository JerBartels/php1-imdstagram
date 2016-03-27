<?php

include_once("classes/Db.class.php");
include_once("classes/User.class.php");
include_once("session.php");
include_once("register.php");

?>

<!doctype html>

<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Satchmo.cc</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles/reset.css">
        <link rel="stylesheet" href="styles/style.css">
    </head>

    <body>
        <div id="register">
            <h1>Satchmo.cc</h1>
            <h2>Share your remarkable moments with your best friends</h2>

            <!-- verwijzing naar register.php -->
            <form action="register.php" method="post">
                <?php if(isset($_POST['username']) && !validateUsername($_POST['username'])){echo $err_username;} ?>
                <input type="text" name="username" id="username" placeholder="username" value="<?php if(isset($_POST['username'])){ print $_POST['username']; } ?>">
                <?php if(isset($_POST['firstname']) && !validateFirstname($_POST['firstname'])){echo $err_firstname;} ?>
                <input type="text" name="firstname" id="firstname" placeholder="firstname" value="<?php if(isset($_POST['firstname'])){ print $_POST['firstname']; } ?>">
                <?php if(isset($_POST['lastname']) && !validateLastname($_POST['lastname'])){echo $err_lastname;} ?>
                <input type="text" name="lastname" id="lastname" placeholder="lastname" value="<?php if(isset($_POST['lastname'])){ print $_POST['lastname']; } ?>">
                <?php if(isset($_POST['email']) && !validateEmail($_POST['email'])){echo $err_email;} ?>
                <input type="text" name="email" id="email" placeholder="your@email" value="<?php if(isset($_POST['email'])){ print $_POST['email']; } ?>">
                <?php if(isset($_POST['pass']) && !validatePass($_POST['pass'])){echo $err_pass;} ?>
                <input type="password" name="pass" id="pass" placeholder="******" value="<?php if(isset($_POST['pass'])){ print $_POST['pass']; } ?>">
                <input type="submit" name="signup" id="signup" value="sign up">
            </form>
            <p id="feedback">
                <?php echo $feedback ?>
            </p>
        </div>

        <hr >

        <div id="login">
            <form action="" method="post">
                <input type="text" name="l_username" id="l_username" placeholder="username">
                <input type="password" name="l_pass" id="l_pass" placeholder="******">
                <input type="submit" name="signin" id="signin" value="sign in">
            </form>
        </div>

    </body>

</html>