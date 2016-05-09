<?php

require_once("init.php");

//kijken of user al ingelogd is
if(!isset($_SESSION["username"]))
{
    header("location: ../index.php");
}

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
                $post->Likes = 0;
                $post->Date = $date_post;
                $post->Inapp = 0;
                $post->Filter = $_POST["post_filter"];

                /*switch($_POST["post_rotation"])
                {
                    case '90':
                        $post->Rotation = "ninety_degrees";
                        break;
                    case '180':
                        $post->Rotation = "oneeighty_degrees";
                        break;
                    case '270':
                        $post->Rotation = "twoseventy_degrees";
                        break;
                    default:
                        $post->Rotation = "";
                        break;
                }*/


                if($_POST["location_post"] != "")
                {
                    $post->City = $_POST["location_post"];
                }
                else
                {
                    $post->City = "Undefined";
                }

                $post->Save();
                $feedback_post = "you rock!";
            }
        }
        else
        {
            $feedback_post = "all fields need input.";
        }
    }
    catch(Exception $e)
    {
        $feedback_post = $e->getMessage();
    }
}

//post deleten
if(isset($_POST["feed-delete-button"]))
{
    try
    {
        $postToDelete = $_POST["feed-delete-post"];
        $post = new Post();
        $post->Delete($postToDelete);
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
    <link rel="stylesheet" href="../styles/cssgram.css">
    <link rel="stylesheet" href="../styles/satchmo.css">
</head>

<body>

<nav>
    <div class="nav_content">

        <div class="nav_left" style="visibility: hidden">
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
    <div>
        <p id="geolocation"></p>
    </div>

<!--    <div id="profile">
        <div id="profile_content">
            <?php /*echo '<img src="../assets/' . $user->ProfilePic . '"alt="profile_pict" class="profile_pict">' */?>
            <h1><?php /*print $user->Firstname . " " . $user->Lastname*/?></h1>
            <a class="btn_a" href="profile.php">change profile</a>
        </div>
    </div>-->

    <div id="post_zone">
        <div id="post_zone_content">
            <div id="image_preview">
                <figure id="figure_preview">
                    <img id="preview" src="#" alt="your profile image">
                </figure>
            </div>

            <form enctype="multipart/form-data" method="post" action="" autocomplete="off">
                <!--<input type="hidden" name="MAX_FILE_SIZE" value="32768"/>-->
                <input type="file" class="post_post" name="post_post" id="post_post">
                <input type="button" id="post_img" class="post_img" value="select picture">
                <select name="post_filter" id="post_filter">
                    <option value="" selected>no filter</option>
                    <option value="aden">aden</option>
                    <option value="reyes">reyes</option>
                    <option value="perpetua">perpetua</option>
                    <option value="inkwell">inkwell</option>
                    <option value="toaster">toaster</option>
                    <option value="walden">walden</option>
                    <option value="hudson">hudson</option>
                    <option value="gingham">gingham</option>
                    <option value="mayfair">mayfair</option>
                    <option value="lofi">lo-fi</option>
                    <option value="xpro2">x pro ii</option>
                    <option value="_1977">1977</option>
                    <option value="brooklyn">brooklyn</option>
                    <option value="nashville">nashville</option>
                    <option value="lark">lark</option>
                    <option value="moon">moon</option>
                    <option value="clarendon">clarendon</option>
                    <option value="willow">willow</option>
                </select>
                <input type="input" class="post_post" name="input_post" id="input_post" placeholder="who what where when">
                <input type="hidden" class="post_post" name="location_post" id="location_post">
                <div class="button_center">
                    <input type="submit" value="ok" name="btn_post" id="btn_post">
                    <input type="reset" value="x" id='btn_reset'>
                </div>
            </form>

            <p class="form_feedback"><?php echo $feedback_post ?></p>

        </div>
    </div>
    <div class="feed">

            <?php

                //alle posts ophalen uit DB + Count op 20 zetten
                //na post posten laten, zodat de nieuwe foto direct getoond wordt
                $post = new Post();
                $posts = $post->getAllPosts();
                $actualTime = new DateTime();

                $follow = new Follow();

                foreach($posts as $post)
                {
                    if($follow->AlreadyAcceptedFan($user->Username, $post["username"]) || $post["username"] == $user->Username)
                    {
                        if ($post["inapp"] < 3) {
                            {
                                $like = new Likes();
                                $like->Username = $post["username"];
                                $like->Picture = $post["photo"];

                                $postTime = new DateTime($post["date"]);
                                $sincePost = $postTime->diff($actualTime);
                                $formattedTime;

                                if ($sincePost->d >= 1) {
                                    $formattedTime = $sincePost->d . ' dagen';
                                } else {
                                    $formattedTime = $sincePost->h . ' uren, ' . $sincePost->i . ' min';
                                }


                                ?>

                                <div class="feed-feed">

                                    <div class="feed-id">
                                        <div class="feed-id-username">
                                            <span><a href="user.php?username=<?php echo $post["username"] ?>"><?php echo $post["username"] ?></a></span>
                                        </div>
                                        <div class="feed-id-date"><span><?php echo $formattedTime ?> / <span><a href="search.php?city_search=<?php echo $post["city"] ?>"><?php echo $post["city"] ?></a></span></span></div>
                                    </div>

                                    <div class="feed-image">
                                        <figure class="<?php echo $post["filter"] . " " . $post["rotation"]?>">
                                            <?php print '<img src="../assets/posts/' . $post["photo"] . '"alt="feed_pict_img" class="feed_pict_img">' ?>
                                        </figure>
                                    </div>

                                    <div class="feed-comment-list">

                                        <div class="feed-like-form">
                                            <span class="number_feed_like"><?php echo $post["likes"] ?> likes</span>
                                            <?php print '<span class="btn_feed_like" id="btn_' . $post["photo"] . '"></span>' ?>
                                        </div>

                                        <div class="feed_inap_form">
                                            <span class="number_feed_inapp"><?php echo $post["inapp"] ?> inapps</span>
                                            <?php print '<span class="btn_feed_inapp" id="btn_inapp_' . $post["photo"] . '"></span>' ?>
                                        </div>

                                        <?php print '<ul id="' . $post["id"] . '">' ?>
                                        <li>
                                            <span class="feed-comment-list-username"><a href="user.php?username=<?php echo $post["username"] ?>"><?php echo $post["username"] ?></a></span><?php echo $post["comment"] ?>
                                        </li>

                                        <?php

                                        $post_id = $post["id"];
                                        $comment = new Comment();
                                        $comments = $comment->getComments($post_id);

                                        foreach ($comments as $comment)
                                        {
                                            print '<li><span class="feed-comment-list-username"><a href="user.php?username=' . $comment["username"] . '">' . $comment["username"] .  '</a></span>' . $comment["comment"] . '</li>';
                                        }

                                        ?>

                                        </ul>

                                        <form action="" method="post" class="feed_comment_form">
                                            <?php print '<input type="input" placeholder="add comment..." name="input_post_comment" class="input_post_comment" id="input_' . $post["id"] . '">'; ?>
                                            <?php print '<button type="submit" value="submit" name="btn_post_comment" class="btn_post_comment" id="btn_' . $post["id"] . '">'; ?>
                                        </form>

                                    </div>

                                    <div class="feed-delete">
                                        <?php
                                        if ($post["username"] == $_SESSION["username"]) {
                                            print '<form class="feed-delete-form" action="" method="post">';
                                            print '<input type="hidden" class="feed-delete-post" name="feed-delete-post" value="' . $post["id"] . '">';
                                            print '<input class="feed-delete-button" name="feed-delete-button" type="submit" value="x">';
                                            print '</form>';
                                        }
                                        ?>
                                    </div>

                                </div>

                                <?php


                            }
                        }
                    }
                }
            ?>

    </div>

    <div class="feed_more">
        <form action="../ajax/load-more.php" method="post">
            <input type="hidden" id="result_no" value="2">
            <input type="submit" id="load" value="v" name="load">
        </form>
    </div>

    <div id="footer">

    </div>


</div>


<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
<script src="../scripts/script.js"></script>
</body>
</html>