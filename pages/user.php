<?php

require_once("../classes/Post.class.php");
require_once("../classes/User.class.php");

if(isset($_GET["username"]))
{
    try
    {
        $user = new User();
        $selected_user = $user->getUserByUsername($_GET["username"]);

        $post = new Post();
        $results = $post->getAllPosts();
    }
    catch(Exception $e)
    {
        echo $e;
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
    </div>

    <div id="results">
        <div id="results_content">
            <?php
            foreach($results as $result)
            {
                if($result["username"] == $selected_user["username"])
                {
                    print '<div class="results_results" style="background-image: url(../assets/posts/' . $result["photo"] . ')">';
                    print '</div>';
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