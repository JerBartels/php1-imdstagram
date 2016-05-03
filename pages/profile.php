<?php

include_once("../classes/Db.class.php");
include_once("../classes/User.class.php");
include_once("../classes/Follow.class.php");
include_once("session.php");
include_once("reglog.php");

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
$user->Private = $db_user["private"];

$follow = new Follow();

//check of button geklikt wordt
if(isset($_POST["save"]))
{
    //eerst oude gebruikersgegevens bewaren
    $old_username = $user->Username;
    $old_email = $user->Email;
    $old_lastname = $user->Lastname;
    $old_firstname = $user->Firstname;
    $old_pass = $user->Pass;

    if(($old_username === $_POST["username"]) && ($old_email === $_POST["email"]) && ($old_lastname === $_POST["lastname"]) && ($old_firstname === $_POST["firstname"]) && ($old_pass === $_POST["pass"]))
    {
        $feedback = "nothing has changed dweepo";
    }
    else
    {
        if (validateInput()) {
            try {
                //dan user updaten
                $user->Username = $_POST["username"];
                $user->Firstname = $_POST["firstname"];
                $user->Lastname = $_POST["lastname"];
                $user->Email = $_POST["email"];

                if($old_pass !==  $_POST["pass"])
                {
                    $pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);
                    $user->Pass = $pass;
                }

                if (!($old_username === $user->Username) && !($old_email === $user->Email)) {
                    //controleren of er al een user bestaat met dit emailadres
                    if ($user->Exists($user->Email, "email")) {
                        $feedback = "Too late! Already in use! :)";
                    } //controleren of de usernaam uniek is
                    else if ($user->Exists($user->Username, "username")) {
                        $feedback = "This username is already in use :(";
                    }
                } //twee maal false, nu effectief aanmaken in db
                else {
                    //user bewaren in DB
                    $user->Update($old_username);

                    //sessie aanmaken zodat tijdens zelfde sessie niet opnieuw ingelogd moet worden
                    $_SESSION["username"] = $user->Username;

                    $feedback = "you're changed!";
                }
            } catch (Exception $e) {
                $feedback = $e->getMessage();
            }
        }
    }
}

if(isset($_POST["btn_profile_pic"]))
{
    try
    {
        //time zorgt ervoor dat het opladen van foto's met dezelfde naam geen overwrite met zich meebrengt
        $profile_picture = time() . $_FILES['profile_pic']['name'];
        if(!empty($profile_picture))
        {
            $target = GW_UPLOADPATH . $profile_picture;
            if(move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target))
            {
                $user->SaveProfilePicture($user->Username, $profile_picture);
                $feedback_profile_pic = "you're still ugly!";
            }
        }
    }
    catch(Exception $e)
    {
        $feedback_profile_pic = $e->getMessage();
    }
}

if(isset($_POST["change"]))
{
    try
    {
        if($_POST["input_change_privacy"] == "private")
        {
            $user->Private = True;
            $user->SetProfilePrivate($user->Username, $user->Private);

            $follow->UpdateAccepted($user->Username, false);

            $feedback_privacy = "your profile is now private.";
        }
        else
        {
            $user->Private = False;
            $user->SetProfilePrivate($user->Username, $user->Private);

            $follow->UpdateAccepted($user->Username, true);

            $feedback_privacy = "your profile became public.";
        }
    }
    catch(Exception $e)
    {
        $feedback_privacy = $e->getMessage();
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
        <a href="../index.php" class="logo_app">logo</a>
        <a href="logout.php" class="logout_app">log out</a>
    </div>
</nav>

<div class="clearfix"></div>

<div class="container">
    <div id="edit_profile">
        <div class="edit_profile_content">
            <h2>Edit profile picture</h2>
            <p class="change_feedback"><?php echo $feedback_profile_pic ?></p>

            <form enctype="multipart/form-data" method="post" action="">
                <!--<input type="hidden" name="MAX_FILE_SIZE" value="32768"/>-->
                <label class=label_change_profile for="profile_pic">Picture</label><input type="file" class="input_change_profile" name="profile_pic" id="profile_pic"><br>
                <input type="submit" value="send" name="btn_profile_pic" class="input_change_profile" id="btn_profile_pic">
            </form>
        </div>

        <div class="full_hr"></div>

        <div class="edit_profile_content">
            <h2>Change profile</h2>
            <p class="change_feedback" id="change_feedback"><?php echo $feedback ?></p>

            <form action="" method="post" autocomplete="off">
                <label class=label_change_profile for="input_change_username">Username</label><input type="text" name="username" id="input_change_username" class="input_change_profile" value="<?php print $user->Username; ?>"><br>
                <?php if(isset($_POST['username']) && !validateUsername($_POST['username'])){echo $err_username;} ?>
                <p id="username_ajax_feedback"></p>
                <label class=label_change_profile for="firstname">Firstname</label><input type="text" name="firstname" id="firstname" class="input_change_profile" value="<?php print $user->Firstname; ?>"><br>
                <?php if(isset($_POST['firstname']) && !validateFirstname($_POST['firstname'])){echo $err_firstname;} ?>
                <label class=label_change_profile for="lastname">Lastname</label><input type="text" name="lastname" id="lastname" class="input_change_profile" value="<?php print $user->Lastname; ?>"><br>
                <?php if(isset($_POST['lastname']) && !validateLastname($_POST['lastname'])){echo $err_lastname;} ?>
                <label class=label_change_profile for="email">Email</label><input type="text" name="email" id="email" class="input_change_profile" value="<?php print $user->Email; ?>"><br>
                <?php if(isset($_POST['email']) && !validateEmail($_POST['email'])){echo $err_email;} ?>
                <label class=label_change_profile for="pass">Password</label><input type="password" name="pass" id="pass" class="input_change_profile" value="<?php print $user->Pass; ?>"><br>
                <?php if(isset($_POST['pass']) && !validatePass($_POST['pass'])){echo $err_pass;} ?>
                <input type="submit" name="save" id="btn_save" class="button input_change_profile" value="send" />
            </form>
        </div>

        <div class="full_hr"></div>

        <div class="edit_profile_content">
            <h2>Privacy</h2>
            <p class="change_feedback" id="change_feedback"><?php echo $feedback_privacy ?></p>

            <form action="" method="post" autocomplete="off">
                <label class=label_change_profile for="input_change_privacy">Profile type</label>
                <select name="input_change_privacy" id="input_change_privacy">
                    <option value="private"<?=$user->Private == True ? ' selected="selected"' : '';?>>Private</option>
                    <option value="public"<?=$user->Private == False ? ' selected="selected"' : '';?>>Public</option>
                </select>
                <input type="submit" name="change" id="btn_privacy" class="button input_change_profile" value="send" />
            </form>
        </div>

        <div class="full_hr"></div>

        <div class="edit_profile_content">
            <h2>Accept love requests</h2>
            <p class="change_feedback" id="change_feedback"><?php echo $feedback_love_requests ?></p>

            <ul>
                <?php

                    $follows = $follow->getAllFollows($user->Username);

                    foreach($follows as $follow)
                    {
                ?>

                        <li><?php echo $follow["fan"] ?></li>
                <?php
                    }
                ?>
            </ul>

            <form action="" method="post" autocomplete="off">
                <label class=label_change_profile for="input_change_privacy">Profile type</label>
                <select name="input_change_privacy" id="input_change_privacy">
                    <option value="private"<?=$user->Private == True ? ' selected="selected"' : '';?>>Private</option>
                    <option value="public"<?=$user->Private == False ? ' selected="selected"' : '';?>>Public</option>
                </select>
                <input type="submit" name="change" id="btn_privacy" class="button input_change_profile" value="send" />
            </form>
        </div>

        <div class="full_hr"></div>

    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="../scripts/script.js"></script>
</body>
</html>