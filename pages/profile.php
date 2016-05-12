<?php

//classes
include_once "init.php" ;

//specific pages
include_once "session.php";
include_once "reglog.php";

//kijken of user al ingelogd is
if(!isset($_SESSION["username"]))
{
    header("location: ../index.php");
}

//upload path
define('GW_UPLOADPATH', '../assets/');

//Set default timezone
date_default_timezone_set(date_default_timezone_get());

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
$comment = new Comment();
$post = new Post();

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
                $user->Username = strip_tags($_POST["username"]);
                $user->Firstname = strip_tags($_POST["firstname"]);
                $user->Lastname = strip_tags($_POST["lastname"]);
                $user->Email = strip_tags($_POST["email"]);

                if($old_pass !==  $_POST["pass"])
                {
                    $pass = password_hash($_POST["pass"], PASSWORD_DEFAULT);
                    $user->Pass = $pass;
                }

                if (!($old_username === $user->Username) && !($old_email === $user->Email)) {
                    //controleren of er al een user bestaat met dit emailadres
                    if ($user->Exists($user->Email, "email")) {
                        $feedback = "too late! already in use! :)";
                    } //controleren of de usernaam uniek is
                    else if ($user->Exists($user->Username, "username")) {
                        $feedback = "this username is already in use :(";
                    }
                } //twee maal false, nu effectief aanmaken in db
                else {
                    //user bewaren in DB
                    $user->Update($old_username);
                    //tabellen die gebruik maken van username updaten
                    $follow->updateUsernameFan($old_username, $user->Username);
                    $follow->updateUsernameTarget($old_username, $user->Username);
                    $comment->updateUsername($old_username, $user->Username);
                    $post->updateUsername($old_username, $user->Username);

                    //sessie aanmaken zodat tijdens zelfde sessie niet opnieuw ingelogd moet worden
                    $_SESSION["username"] = $user->Username;
                    $feedback = "profile changed!";
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
                $user->ProfilePic = strip_tags($target);
                $user->SaveProfilePicture($user->Username, $profile_picture);
                $feedback_profile_pic = "nice one!";
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

if(isset($_POST["btn_accept_love"]))
{
    try
    {
        $accepted = true;
        $follow->AcceptFollow($_POST["input_accept_love"], $user->Username, $accepted);
        $feedback_love_requests = "you've accepted the love of " . $_POST["input_accept_love"] . " :)";
    }
    catch(Exception $e)
    {
        $feedback_love_requests = $e->getMessage();
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
    <link rel="stylesheet" href="../styles/satchmo.css">
</head>

<body>

<nav>
    <div class="nav_content">

        <div class="nav_left">
            <a href="../index.php" class="nav_back a_nav">back</a>
        </div>

        <div class="clearfix"></div>

        <div class="nav_right">
            <a class="a_search a_nav" href="#">search</a>
            <a class="a_profile a_nav" href="profile.php">profile</a>
            <a class="a_logout a_nav" href="logout.php">logout</a>
        </div>

        <div class="clearfix"></div>

        <div class="nav_search">
            <form method="post" action="search.php" class ="form_nav" autocomplete="off">
                <input type="text" placeholder="search" class="submit_input" name="input_search">
                <input type="submit" value="find" name="submit_search" class="submit_search" id="submit_search">
            </form>
        </div>

        <div class="clearfix"></div>

    </div>
</nav>

<div class="container">

    <div class="summary">
        <div class="summary_content">
            <div>
                <h1><?php echo $user->Username ?></h1>
                <h3><?php echo $user->Firstname . " " . $user->Lastname ?></h3>
                <div class="profile_pict">
                    <img id="img_profile_pict" src="../assets/<?php echo $user->ProfilePic ?>" alt="profile-pic">
                </div>
            </div>
        </div>
    </div>

    <?php

    $follows = $follow->getAllFollows($user->Username);

    if($user->Private && count($follows)>0){ ?>

        <div class="edit_profile_content">
            <p class="title">accept love requests</p>

            <ul>
                <?php

                foreach($follows as $follow)
                {
                    ?>
                    <form action="" method="POST" class="profile_form">
                        <label for=""><a href="user.php?username=<?php echo $follow["fan"] ?>"><?php echo $follow["fan"] ?></a></label>
                        <input type="hidden" value="<?php echo $follow["fan"] ?>" name="input_accept_love">
                        <input type="submit" value="ok" name="btn_accept_love">
                    </form>

                    <p class="change_feedback" id="change_feedback"><?php echo $feedback_love_requests ?></p>

                    <?php
                }
                ?>
            </ul>

        </div>

    <?php } ?>


    <div id="edit_profile">
        <div class="edit_profile_content">
            <p class="title">edit picture</p>

            <form method="post" action="" class="profile_form" enctype="multipart/form-data" >
                <input type="file" class="input_change_profile" name="profile_pic" id="profile_pic">
                <input type="button" id="post_profile_img" class="post_img" value="select picture">
                <div class="button_center">
                    <input type="submit" value="send" name="btn_profile_pic" class="input_change_profile" id="btn_profile_pic">
                </div>
            </form>

            <p class="change_feedback"><?php echo $feedback_profile_pic ?></p>
        </div>

        <div class="full_hr"></div>

        <div class="edit_profile_content">
            <p class="title">change profile</p>

            <form action="" method="post" autocomplete="off" class="profile_form">
                <label class=label_change_profile for="input_change_username">Username: </label><input type="text" name="username" id="input_change_username" class="input_change_profile" value="<?php print $user->Username; ?>">
                <?php if(isset($_POST['username']) && !validateUsername($_POST['username'])){echo $err_username;} ?>
                <p id="username_ajax_feedback"></p>
                <label class=label_change_profile for="firstname">Firstname: </label><input type="text" name="firstname" id="firstname" class="input_change_profile" value="<?php print $user->Firstname; ?>">
                <?php if(isset($_POST['firstname']) && !validateFirstname($_POST['firstname'])){echo $err_firstname;} ?>
                <label class=label_change_profile for="lastname">Lastname: </label><input type="text" name="lastname" id="lastname" class="input_change_profile" value="<?php print $user->Lastname; ?>">
                <?php if(isset($_POST['lastname']) && !validateLastname($_POST['lastname'])){echo $err_lastname;} ?>
                <label class=label_change_profile for="email">Email: </label><input type="text" name="email" id="email" class="input_change_profile" value="<?php print $user->Email; ?>">
                <?php if(isset($_POST['email']) && !validateEmail($_POST['email'])){echo $err_email;} ?>
                <label class=label_change_profile for="pass">Password: </label><input type="password" name="pass" id="pass" class="input_change_profile" value="<?php print $user->Pass; ?>">
                <?php if(isset($_POST['pass']) && !validatePass($_POST['pass'])){echo $err_pass;} ?>
                <div class="button_center">
                    <input type="submit" name="save" id="btn_save" class="button input_change_profile" value="send" />
                </div>
            </form>

            <p class="change_feedback" id="change_feedback"><?php echo $feedback ?></p>

        </div>

        <div class="edit_profile_content">
            <p class="title">privacy</p>

            <form action="" method="post" autocomplete="off" class="profile_form">
                <label class=label_change_profile for="input_change_privacy">Profile type: </label>
                <select name="input_change_privacy" id="input_change_privacy">
                    <option value="private"<?=$user->Private == True ? ' selected="selected"' : '';?>>private</option>
                    <option value="public"<?=$user->Private == False ? ' selected="selected"' : '';?>>public</option>
                </select>
                <div class="button_center">
                    <input type="submit" name="change" id="btn_privacy" class="button input_change_profile" value="send" />
                </div>
            </form>

            <p class="change_feedback" id="change_feedback"><?php echo $feedback_privacy ?></p>
        </div>

    </div>
</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="../scripts/script.js"></script>
</body>
</html>