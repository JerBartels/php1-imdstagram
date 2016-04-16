<?php

include_once("../classes/Db.class.php");
include_once("../classes/User.class.php");
include_once("../classes/Post.class.php");
include_once("session.php");
include_once("reglog.php");
//include_once("search.php");
//include_once("../ajax/load-more.php");


//upload path
define('GW_UPLOADPATH', '../assets/posts/');

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

//post posten
if(isset($_POST["btn_post"]))
{
    try
    {
        //naam van de foto die opgeladen wordt
        //time zorgt ervoor dat het opladen van foto's met dezelfde naam geen overwrite met zich meebrengt
        $post_post = time() . $_FILES['post_post']['name'];
        $input_post = $_POST['input_post'];
        $date_post = date('Y-m-d H:i:s', time());


        if(!($_FILES['post_post']['size'] == 0) && !empty($input_post))
        {
            $target = GW_UPLOADPATH . $post_post;

            if(move_uploaded_file($_FILES['post_post']['tmp_name'], $target))
            {
                $post = new Post();
                $post->Photo = $post_post;
                $post->Comment = $input_post;
                $post->Username = $user->Username;
                $post->Date = $date_post;

                $post->Save();
                $feedback_post = "You rock!";
            }
        }
        else
        {
            $feedback_post = "All fields need input, doefus!";
        }
    }
    catch(Exception $e)
    {
        $feedback_post = $e->getMessage();
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

<div id="search">
    <div id="search_content">
        <form method="post" action="search.php" class ="form_nav">
            <input type="text" placeholder="search" name="input_search">
            <!-- Wordt display:none in css, daar zoeken via enter zal gebeuren -->
            <input type="submit" value="find" name="submit_search" id="submit_search">
        </form>
    </div>
</div>

<div class="container">
    <div id="profile">
        <div id="profile_content">
            <?php echo '<img src="../assets/' . $user->ProfilePic . '"alt="profile_pict" class="profile_pict">' ?>
            <h1><?php print $user->Firstname . " " . $user->Lastname?></h1>
            <a class="btn_a" href="profile.php">change profile</a>
        </div>
    </div>
    <div id="post_zone">
        <div id="post_zone_content">
            <form enctype="multipart/form-data" method="post" action="">
                <!--<input type="hidden" name="MAX_FILE_SIZE" value="32768"/>-->
                <input type="file" class="post_post" name="post_post" id="post_post"><br>
                <input type="input" class="post_post" name="input_post" id="input_post"><br>
                <input type="submit" value="post" name="btn_post" id="btn_post">
                <p class="form_feedback"><?php echo $feedback_post ?></p>
            </form>
        </div>
    </div>
    <div id="feed">


            <?php

                //alle posts ophalen uit DB + Count op 20 zetten
                //na post posten laten, zodat de nieuwe foto direct getoond wordt
                $post = new Post();
                $posts = $post->getPosts(0,5);
                $count = 5;

                foreach($posts as $post)
                {
                    {
                        print '<div class="feed_feed"><div class="feed_username"><span>' . $post["username"] . '</span></div>';
                        print '<div class="feed_date"><span>' . $post["date"] . '</span></div>';
                        print '<img src="../assets/posts/' . $post["photo"] . '"alt="feed_pict_img" class="feed_pict_img">';
                        print '<div class="feed_comment"><span class="comment_username">' . $post["username"] . "</span><span class='comment_text'>" . $post["comment"] . '</span></div></div>';
                    }

                    if($count <= 0)
                    {
                        break;
                    }

                    $count--;
                }
            ?>

    </div>

    <div class="feed_more">
        <div class="feed_more_content">
            <form action="../ajax/load-more.php" method="post">
                <input type="hidden" id="result_no" value="2">
                <input type="submit" id="load" value="load more" name="load">
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