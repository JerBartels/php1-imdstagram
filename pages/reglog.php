<?php

//deze pagina dekt zowel het inloggen als het registreren van een nieuw profiel
//om geen gigantische index.php te krijgen deze functies in afzonderlijke file gezet

include_once("init.php");

// --------- ** SIGN UP ** --------- //

$feedback = "";

//check of button geklikt wordt
if(isset($_POST["signup"]))
{
    if(validateInput())
    {
        try
        {
            $default_profilepic = "noprofilepict.jpg";

            $user = new User();
            $user->Username = $_POST["username"];
            $user->Firstname = $_POST["firstname"];
            $user->Lastname = $_POST["lastname"];
            $user->Email = $_POST["email"];
            $user->ProfilePic = $default_profilepic;
            $user->Private = false;

            $pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);
            $user->Pass = $pass;

            //controleren of er al een user bestaat met dit emailadres
            if($user->Exists($user->Email, "email"))
            {
                $feedback = "we know you!";
            }
            //controleren of de usernaam uniek is
            else if($user->Exists($user->Username, "username"))
            {
                $feedback = "this username is already taken :(";
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

// --------- ** SIGN IN ** --------- //

if(isset($_POST["signin"]))
{
    try
    {
        $user = new User();

        if($user->Exists($_POST["l_username"], "username"))
        {
            if($user->LogIn($_POST["l_username"], $_POST["l_pass"]))
            {
                //gebruiker bestaat in DB en PW is juist dus we mogen alvast de username koppelen aan de gebruiker
                $user->Username = $_POST["l_username"];

                //ook hier starten we de sessie
                $_SESSION["username"] = $user->Username;

                //doorverwijzen naar de application pagina
                header("location: satchmo.php");
            }
            else
            {
                //error als passwoord niet correct is.
                $l_feedback = "forgot your password?";
            }
        }
        else
        {
            //error als user niet bestaat in DB
            $l_feedback = "we do not know you!";
        }
    }
    catch(Exception $e)
    {
        $feedback = $e->getMessage();
    }
}

// --------- ** VALIDATE INPUT ** --------- //

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
        $err_firstname = "<span class='form_error'>no name?</span>";
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
        $err_lastname = "<span class='form_error'>incognito?</span>";
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
        $err_username = "<span class='form_error'>no username?</span>";
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
            $err_email = "<span class='form_error'>emailaddress not valid</span>";
        }
    }
    else
    {
        global $err_email;
        $err_email = "<span class='form_error'>no email?</span>";
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
            $err_pass = "<span class='form_error'>strong password required</span>";
        }
    }
    else
    {
        global $err_pass;
        $err_pass = "<span class='form_error'>no password?</span>";
    }
}

?>