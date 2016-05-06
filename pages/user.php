<?php

require_once("init.php");

//kijken of user al ingelogd is
if(!isset($_SESSION["username"]))
{
    header("location: ../index.php");
}

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

            //als user public is mogen de posts altijd getoond worden
            if(!$selected_user["private"])
            {
                foreach ($results as $result)
                {
                    if ($result["username"] == $selected_user["username"])
                    {
                        ?>

                        <figure class="<?php echo $result["filter"] ?>">
                            <a href="detail.php?post=<?php echo $result["id"] ?>">
                                <img class=results_results src="../assets/posts/<?php echo $result["photo"] ?>"
                                     alt="<?php echo $_POST["input_search"] ?>">
                            </a>
                        </figure>

                        <?php
                    }
                }
            }

            //als user private is mogen de posts enkel getoond worden als de follow geaccepteerd werd
            else
            {
                if($follow->AlreadyAcceptedFan($_SESSION["username"], $selected_user["username"]) || $result["username"] == $selected_user["username"])
                {
                    foreach($results as $result)
                    {
                        if($result["username"] == $selected_user["username"])
                        {
                            ?>

                            <figure class="<?php echo $result["filter"] ?>">
                                <a href="detail.php?post=<?php echo $result["id"]?>">
                                    <img class=results_results src="../assets/posts/<?php echo $result["photo"] ?>" alt="<?php echo $_POST["input_search"] ?>">
                                </a>
                            </figure>

            <?php
                        }
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