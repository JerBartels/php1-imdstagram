<?php

include_once("../classes/Db.class.php");
include_once("../classes/User.class.php");
include_once("session.php");

//upload path
define('GW_UPLOADPATH', '../assets/');

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
$user->ProfilePic = $db_user["profilepic"];

if(!empty($_POST['username_profile']))
{
    try
    {
        if($user->Username == $_POST['username_profile'])
        {
            $feedback = "Er is niets veranderd :)";
        }

        else if(!$user->Exists($_POST['username_profile'], "username") )
        {
            $old_username = $user->Username;
            $user->Username = $_POST['username_profile'];

            $user->Update($old_username);
        }
        else
        {
            $feedback = "Deze usernaam is al in gebruik flipper!";
        }
    }
    catch(Exception $e)
    {
        $feedback = $e->getMessage();
    }
}

if(isset($_POST["btn_profile_pic"]))
{
    try
    {
        $profile_picture = $_FILES['profile_pic']['name'];

        if(!empty($profile_picture))
        {
            $target = GW_UPLOADPATH . $profile_picture;

            if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target))
            {
                $user->SaveProfilePicture($user->Username, $profile_picture);
                header("location: ../pages/satchmo.php");
            }
        }
    }
    catch(Exception $e)
    {
        $feedback_profile_pic = $e->getMessage();
    }
}



?>

<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Satchmo.cc</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href='https://fonts.googleapis.com/css?family=Lato:400,700italic,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../styles/reset.css">
    <link rel="stylesheet" href="../styles/style.css">
</head>

<body>

<nav id="nav">
    <div id="nav_content">
        <a href="index.php" class="logo_app">logo</a>
        <a href="logout.php" class="logout_app">log out</a>
    </div>
</nav>

<div class="clearfix"></div>

<div class="container">
    <div id="profile">
        <div id="profile_content">
            <?php echo '<img src="../assets/' . $user->ProfilePic . '"alt="profile_pict" class="profile_pict">' ?>

            <form enctype="multipart/form-data" method="post" action="" id="profile_pict_form">
                <!--<input type="hidden" name="MAX_FILE_SIZE" value="32768"/>-->
                <input type="file" id="profile_pic" class="profile_pic" name="profile_pic">
                <input type="submit" value="add profile pic" name="btn_profile_pic">
            </form>
            <p>
                <?php echo $feedback_profile_pic ?>
            </p>

            <h2><?php print $user->Firstname . " " . $user->Lastname?></h2>
            <button id="btn_change_profile">profiel bewerken</button>
            <div id="profile_form">
                <form action="" method="post">
                    <input type="text" class="input_profile" name="username_profile" id="username_profile" value="<?php print $user->Username; ?>">
                    <input type="text" class="input_profile" name="firstname_profile" id="firstname_profile" value="<?php print $user->Firstname; ?>">
                    <input type="text" class="input_profile" name="lastname_profile" id="lastname_profile" value="<?php print $user->Lastname; ?>">
                    <input type="text" class="input_profile" name="email_profile" id="email_profile" value="<?php print $user->Email; ?>">
                    <input type="password" class="input_profile" name="pass_profile" id="pass_profile" value="<?php print $user->Pass; ?>">
                    <input type="submit" class="button" name="save" id="save" value="save" />
                </form>
            </div>
            <p id="feedback">
                <?php echo $feedback ?>
            </p>
        </div>
    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="../scripts/script.js"></script>
</body>
</html>