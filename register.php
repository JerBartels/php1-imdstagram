<?php

include_once("classes/Db.class.php");
include_once("classes/User.class.php");
include_once("session.php");

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
