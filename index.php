<?php

include_once("classes/Db.class.php");
include_once("classes/User.class.php");
include_once("session.php");
include_once("register.php");

$feedback = "";

//kijken of user al ingelogd is
alreadyLoggedIn();

//check of button geklikt wordt
if(isset($_POST["signup"])){
    if(validateInput())
    {
        try
        {
            $user = new User();
            $user->Username = $_POST["username"];
            $user->Firstname = $_POST["firstname"];
            $user->Lastname = $_POST["lastname"];
            $user->Email = $_POST["email"];
            //BCRYPT met een complexiteit || kost van 12
            $wachtwoord = password_hash($_POST[wachtwoord], PASSWORD_BCRYPT, ['cost' => 12]);

            //controleren of er al een user bestaat met dit emailadres
            if($user->Exists($user->Email, "email"))
            {
                $feedback = "You already are a Satchmo member :)";
            }
            //controleren of de usernaam uniek is
            else if($user->Exists($user->Username, "username"))
            {
                $feedback = "This username is already in use :(";
            }
            //twee maal false, nu effectief aanmaken in db
            else
            {
                //user bewaren in DB
                $user->Save();

                //sessie aanmaken zodat tijdens zelfde sessie niet opnieuw ingelogd moet worden
                $_SESSION["username"] = $user->Username;

                //redirect naar applicatie
                header("location: satchmo.php");
            }
        }
        catch(Exception $e)
        {
            $feedback = $e->getMessage();
        }
    }
}

function validateInput()
{
    if(validateFirstName($_POST["firstname"]) && validateLastName($_POST["lastname"]) && validateUsername($_POST["username"]) && validateEmail($_POST["email"]) && validatePass($_POST["pass"]))
    {
        return true;
    }
    else
    {
        return false;
    }
}

//controleren van form input tijdens ingave
function validateFirstName($p_vValue)
{
    if(!empty($p_vValue))
    {
        return true;
    }
    else
    {
        global $err_firstname;
        $err_firstname = "<span class='form_error'>Hi cowboy, what's your name?</span>";
    }
}

function validateLastName($p_vValue)
{
    if(!empty($p_vValue))
    {
        return true;
    }
    else
    {
        global $err_lastname;
        $err_lastname = "<span class='form_error'>Hi shy-bee, we won't bite!</span>";
    }
}

function validateUsername($p_vValue)
{
    if (!empty($p_vValue))
    {
        return true;
    }
    else
    {
        global $err_username;
        $err_username = "<span class='form_error'>Usernames are awesome, don't leave this one empty!</span>";
    }
}

function validateEmail($p_vValue)
{
    if(!empty($p_vValue))
    {
        if (preg_match("/([\w\-]+\@[\w\-]+\.[\w\-]+)/", $p_vValue))
        {
            return true;
        }
        else
        {
            global $err_email;
            $err_email = "<span class='form_error'>Forgot the @?</span>";
        }
    }
    else
    {
        global $err_email;
        $err_email = "<span class='form_error'>No spam for you?</span>";
    }
}

function validatePass($p_vValue)
{
    $uppercase = preg_match('@[A-Z]@', $p_vValue);
    $lowercase = preg_match('@[a-z]@', $p_vValue);
    $number = preg_match('@[0-9]@', $p_vValue);
    $length = strlen($p_vValue < 8);

    if(!empty($p_vValue))
    {
        if($uppercase && $lowercase && $number && $length)
        {
            return true;
        }
        else
        {
            global $err_pass;
            $err_pass = "<span class='form_error'>Hackers are strong, give them a hard time (> 8 + 1x UC + 1x LC + 1x N°)</span>";
        }
    }
    else
    {
        global $err_pass;
        $err_pass = "<span class='form_error'>No password, honey?</span>";
    }
}

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