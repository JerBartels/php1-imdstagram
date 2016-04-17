<?php

include_once("classes/Db.class.php");
include_once("classes/User.class.php");
include_once("pages/session.php");
include_once("pages/reglog.php");

//kijken of user al ingelogd is
alreadyLoggedIn();

?>

<!doctype html>

<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Satchmo.cc</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href='https://fonts.googleapis.com/css?family=Lato:400,700italic,300' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="styles/reset.css">
        <link rel="stylesheet" href="styles/style.css">
    </head>

    <body>

    <div class="container">
        <div id="div_content">
            <div id="div_center">
                <div id="div_form">
                    <header>
                        <a href="index.php" class="logo">logo</a>
                    </header>

                    <div class="hr">

                    </div>

                    <div id="register">
                        <!-- verwijzing naar register.php -->
                        <form action="" method="post" autocomplete="off">
                            <input type="text" name="username" id="username" placeholder="username" value="<?php if(isset($_POST['username'])){ print $_POST['username']; } ?>">
                            <?php if(isset($_POST['username']) && !validateUsername($_POST['username'])){echo $err_username;} ?>
                            <input type="text" name="firstname" id="firstname" placeholder="firstname" value="<?php if(isset($_POST['firstname'])){ print $_POST['firstname']; } ?>">
                            <?php if(isset($_POST['firstname']) && !validateFirstname($_POST['firstname'])){echo $err_firstname;} ?>
                            <input type="text" name="lastname" id="lastname" placeholder="lastname" value="<?php if(isset($_POST['lastname'])){ print $_POST['lastname']; } ?>">
                            <?php if(isset($_POST['lastname']) && !validateLastname($_POST['lastname'])){echo $err_lastname;} ?>
                            <input type="text" name="email" id="email" placeholder="your@email" value="<?php if(isset($_POST['email'])){ print $_POST['email']; } ?>">
                            <?php if(isset($_POST['email']) && !validateEmail($_POST['email'])){echo $err_email;} ?>
                            <input type="password" name="pass" id="pass" placeholder="******" value="<?php if(isset($_POST['pass'])){ print $_POST['pass']; } ?>">
                            <?php if(isset($_POST['pass']) && !validatePass($_POST['pass'])){echo $err_pass;} ?>
                            <input type="submit" name="signup" id="signup" value="sign up">
                        </form>
                        <p class="form_feedback">
                            <?php echo $feedback ?>
                        </p>
                    </div>

                    <div id="login">
                        <form action="" method="post" autocomplete="off">
                            <input type="text" name="l_username" id="l_username" placeholder="username" value="<?php if(isset($_POST['l_username'])){ print $_POST['l_username']; } ?>">
                            <?php if(isset($_POST['l_username']) && !validateUsername($_POST['l_username'])){echo $err_username;} ?>
                            <input type="password" name="l_pass" id="l_pass" placeholder="******" value="<?php if(isset($_POST['l_pass'])){ print $_POST['l_pass']; } ?>">
                            <?php if(isset($_POST['l_pass']) && !validatePass($_POST['l_pass'])){echo $err_pass;} ?>
                            <input type="submit" name="signin" id="signin" value="sign in">
                        </form>
                        <p class="form_feedback">
                            <?php echo $l_feedback ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- end of container -->

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <script src="scripts/script.js"></script>

    </body>

</html>