<?php

require_once("../classes/Post.class.php");

$post = new Post();
$result = $post->searchPosts($_POST["input_detail"]);

$post->Photo = $result[0]["photo"];
$post->Username = $result[0]["username"];
$post->Date = $result[0]["date"];
$post->Comment = $result[0]["comment"];
$post->Filter = $result[0]["filter"];

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
    <link rel="stylesheet" href="../styles/cssgram.css">
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

    <div id="detail_comment">
        <div id="detail_comment_content">
            <div id="detail_photo">
                <?php print '<figure class="' . $post->Filter . '"><img src="../assets/posts/' . $post->Photo . '"alt="feed_pict_img" class="feed_pict_img"></figure>'; ?>
            </div>
            <div id="detail_user">
                <?php print '<h1 class="detail_username_h1"><a href="user.php?username=' . $post->Username . '">' . $post->Username . '</a></h1>' ?>
                <?php print '<p class="detail_comment_p">' .$post->Comment . '</p>' ?>
            </div>
        </div>
    </div>

    <div id="footer">

    </div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="../scripts/script.js"></script>
</body>
</html>