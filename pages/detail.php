<?php

require_once("../classes/Post.class.php");
include_once("../classes/User.class.php");
include_once("../classes/Likes.class.php");
include_once("../classes/Comment.class.php");

$post = new Post();
$result = $post->getPostById($_GET["post"]);

$post->Photo = $result["photo"];
$post->Username = $result["username"];
$post->Date = $result["date"];
$post->Comment = $result["comment"];
$post->Filter = $result["filter"];
$post->Likes = $result["likes"];
$post->Inapp = $result["inapp"];
$post->City = $result["city"];

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

    <div class="feed-feed">

        <div class="feed-id">
            <div class="feed-id-username">
                <span><a href="user.php?username=<?php echo $post->Username ?>"><?php echo $post->Username ?></a></span>
            </div>
            <div class="feed-id-date"><span><?php echo $post->City ?></span></div>
        </div>

            <div class="feed-image">
                <?php print '<figure class="' . $post->Filter . '"><img src="../assets/posts/' . $post->Photo . '"alt="feed_pict_img" class="feed_pict_img"></figure>'; ?>
            </div>

            <div class="feed-comment-list">

                <div class="feed-like-form">
                    <span class="number_feed_like"><?php echo $post->Likes ?> likes</span>
                    <?php print '<span class="btn_feed_like" id="btn_' . $post->Photo . '"> ** like ** </span>' ?>
                </div>

                <div class="feed_inap_form">
                    <span class="number_feed_inapp"><?php echo $post->Inapp ?> inapps</span>
                    <?php print '<span class="btn_feed_inapp" id="btn_inapp_' . $post->Photo . '"> ** inapp ** </span>' ?>
                </div>

                <?php print '<ul id="' . $result["id"] . '">' ?>

                <li>
                    <span class="feed-comment-list-username"><a href="user.php?username=<?php echo $post->Username ?>"><?php echo $post->Username ?></a></span><?php echo $post->Comment ?>
                </li>

                <?php

                $post_id = $result["id"];
                $comment = new Comment();
                $comments = $comment->getComments($post_id);

                foreach ($comments as $comment)
                {
                    print '<li><span class="feed-comment-list-username"><a href="user.php?username=' . $comment["username"] . '">' . $comment["username"] .  '</a></span>' . $comment["comment"] . '</li>';
                }

                ?>

                </ul>

                <form action="" method="post" class="feed_comment_form">
                    <?php print '<input type="input" placeholder="Add a comment..." name="input_post_comment" class="input_post_comment" id="input_' . $result["id"] . '">'; ?>
                    <?php print '<input type="submit" name="btn_post_comment" class="btn_post_comment" id="btn_' . $result["id"] . '">'; ?>
                </form>

        </div>
    </div>

    <div id="footer">

    </div>

</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="../scripts/script.js"></script>
</body>
</html>