<?php

require_once("init.php");

//kijken of user al ingelogd is
if(!isset($_SESSION["username"]))
{
    header("location: ../index.php");
}

$post = new Post();

if(isset($_POST["submit_search"]))
{
    try
    {
        $results = $post->searchPosts($_POST["input_search"]);
        $number_of_results = count($results);
    }
    catch(Exception $e)
    {
        echo $e;
    }
}

if(isset($_GET["city_search"]))
{
    try
    {
        $results = $post->searchPosts($_GET["city_search"]);
        $number_of_results = count($results);
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

<div class="clearfix"></div>

<div class="container_search">

    <div class="summary">
        <div class="summary_content">
            <h1>#<?php echo $_POST["input_search"] . "" . $_GET["city_search"]?></h1>
            <p><?php echo  $number_of_results ?> posts</p>
        </div>
    </div>

    <div class="results">
        <div class="results_content">
            <?php
                foreach($results as $result)
                {
                    {
                        ?>

                        <div class="figure_cell">
                            <figure class="figure_square <?php echo $result["filter"] ?>">
                                <a href="detail.php?post=<?php echo $result["id"]?>">
                                    <img class=results_results src="../assets/posts/<?php echo $result["photo"] ?>" alt="<?php echo $_POST["input_search"] ?>">
                                </a>
                            </figure>
                        </div>

                        <?php

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