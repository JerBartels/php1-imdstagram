<?php

require_once("../classes/Post.class.php");
require_once("../classes/User.class.php");
require_once("../classes/Follow.class.php");
require_once("../pages/reglog.php");

if(isset($_GET["username"]))
{
    try
    {
        $user = new User();
        $selected_user = $user->getUserByUsername($_GET["username"]);
        $active_user = $user->getUserByUsername($_SESSION["username"]);

        $post = new Post();
        $results = $post->getAllPosts();

        $follow = new Follow();
    }
    catch(Exception $e)
    {
        echo $e;
    }
}

if(isset($_POST["btn_love"]))
{
    try
    {
        $follow->Fan = $active_user["username"];
        $follow->Target = $selected_user["username"];

        if($selected_user["private"])
        {
            $follow->Accepted = false;
        }
        else
        {
            $follow->Accepted = true;
        }

        $follow->Save();
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
    }
}

if(isset($_POST["btn_hate"]))
{
    try
    {
        $follow->Fan = $active_user["username"];
        $follow->Target = $selected_user["username"];
        $follow->DeleteFollow($follow->Fan, $follow->Target);
    }
    catch(Exception $e)
    {
        echo $e->getMessage();
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

<div>

    <div id="summary">
        <div id="summary_content">
            <h1><?php echo $selected_user["username"] ?></h1>
            <img src="../assets/<?php echo $selected_user["profilepic"] ?>" alt="profile-pic" class="profile_pict">
        </div>
        <div>
            <?php
                if($follow->AlreadyFan($active_user["username"],$selected_user["username"])){
            ?>
                    <form action="" method="post">
                        <input type="submit" value="break my heart" name="btn_hate">
                    </form>
            <?php
                }
                else {
            ?>
                    <form action="" method="post">
                        <input type="submit" value="love me" name="btn_love">
                    </form>
            <?php
                }
            ?>
        </div>
    </div>

    <div id="results">
        <div id="results_content">
            <?php

            $follow = new Follow();

            if($selected_user["private"])
            {
                if($follow->AlreadyAcceptedFan($_SESSION["username"], $selected_user["username"]))
                {
                    foreach($results as $result)
                    {
                        if($result["username"] == $selected_user["username"])
                        {
                            print '<div class="results_results" style="background-image: url(../assets/posts/' . $result["photo"] . ')">';
                            print '</div>';
                        }
                    }
                }
            }
            else
            {
                foreach($results as $result)
                {
                    if($result["username"] == $selected_user["username"])
                    {
                        print '<div class="results_results" style="background-image: url(../assets/posts/' . $result["photo"] . ')">';
                        print '</div>';
                    }
                }
            }


            ?>
        </div>
    </div>

    <div class="clearfix"></div>

    <div id="footer">

    </div>


</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="../scripts/script.js"></script>
</body>
</html>