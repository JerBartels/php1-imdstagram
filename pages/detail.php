<?php

require_once("init.php");

//kijken of user al ingelogd is
if(!isset($_SESSION["username"]))
{
    header("location: ../index.php");
}

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

$postTime = new DateTime($post->Date);
$actualTime = new DateTime();
$sincePost = $postTime->diff($actualTime);
$formattedTime;

if ($sincePost->d >= 1) {
    $formattedTime = $sincePost->d . ' dagen';
} else {
    $formattedTime = $sincePost->h . ' uren, ' . $sincePost->i . ' min';
}

if ($sincePost->d >= 1) {
    $formattedTime = $sincePost->d . ' dagen';
} else {
    $formattedTime = $sincePost->h . ' uren, ' . $sincePost->i . ' min';
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
    <link rel="stylesheet" href="../styles/cssgram.css">
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

    <div class="feed">
        <div class="feed-feed">

            <div class="feed-id">
                <div class="feed-id-username">
                    <span><a href="user.php?username=<?php echo $post->Username ?>"><?php echo $post->Username ?></a></span>
                </div>
                <div class="feed-id-date"><span><?php echo $formattedTime ?> / <span><a href="search.php?city_search=<?php echo $post->City ?>"><?php echo $post->City ?></a></span></span></div>
            </div>

            <div class="feed-image">
                <figure class="<?php echo $post->Filter?>">
                    <?php print '<img src="../assets/posts/' . $post->Photo . '"alt="feed_pict_img" class="feed_pict_img">' ?>
                </figure>
            </div>

            <div class="feed-comment-list">

                <div class="feed-like-form">
                    <span class="number_feed_like"><?php echo $post->Likes ?> likes</span>
                    <?php print '<span class="btn_feed_like" id="btn_' . $post->Photo . '"></span>' ?>
                </div>

                <div class="feed_inap_form">
                    <span class="number_feed_inapp"><?php echo $post->Inapp ?> inapps</span>
                    <?php print '<span class="btn_feed_inapp" id="btn_inapp_' . $post->Photo . '"></span>' ?>
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
                    <?php print '<input type="input" placeholder="add comment..." name="input_post_comment" class="input_post_comment" id="input_' . $result["id"] . '">'; ?>
                    <?php print '<button type="submit" value="submit" name="btn_post_comment" class="btn_post_comment" id="btn_' . $result["id"] . '">'; ?>
                </form>

            </div>

            <div class="feed-delete">
                <?php
                if ($post->Username == $_SESSION["username"]) {
                    print '<form class="feed-delete-form" action="" method="post">';
                    print '<input type="hidden" class="feed-delete-post" name="feed-delete-post" value="' . $result["id"] . '">';
                    print '<input class="feed-delete-button" name="feed-delete-button" type="submit" value="x">';
                    print '</form>';
                }
                ?>
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