<?php

include_once("classes/Db.class.php");
include_once("classes/User.class.php");

$feedback = "";

if(!empty($_POST["username"]) && !empty($_POST["firstname"]) && !empty($_POST["lastname"]) && !empty($_POST["email"]) && !empty($_POST["pass"]))
{
    try
    {
        $user = new User();
        $user->Username = $_POST["username"];
        $user->Firstname = $_POST["firstname"];
        $user->Lastname = $_POST["lastname"];
        $user->Email = $_POST["email"];
        $wachtwoord = password_hash($_POST[wachtwoord], PASSWORD_BCRYPT, ['cost' => 12]);           //BCRYPT met een complexiteit || kost van 12

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
            $user->Save();
            $feedback= "Booeyaaaaaaa! Welkom op Satchmo.cc, " . $user->Firstname . "!";
        }
    }
    catch(Exception $e)
    {
        $feedback = $e->getMessage();
    }
}
else
{
    $feedback = "All fields are obligatory!";
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
        <div>
            <h1>Satchmo.cc</h1>
            <h2>Share your remarkable moments with your best friends</h2>
            <form action="" method="post">
                <input type="text" name="username" id="username" placeholder="username">
                <input type="text" name="firstname" id="firstname" placeholder="firstname">
                <input type="text" name="lastname" id="lastname" placeholder="lastname">
                <input type="email" name="email" id="email" placeholder="your@email">
                <input type="password" name="pass" id="pass" placeholder="******">
                <input type="submit" name="signup" id="signup" value="sign up">
            </form>
            <p id="feedback">
                <?php echo $feedback ?>
            </p>
        </div>
    </body>

</html>