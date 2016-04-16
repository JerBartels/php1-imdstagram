<?php

require_once("../classes/Post.class.php");

if(isset($_POST["submit_search"]))
{
    try
    {
        $post = new Post();
        $results = $post->searchPosts($_POST["input_search"]);
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
            <input type="text" value="search" name="input_search">
            <!-- Wordt display:none in css, daar zoeken via enter zal gebeuren -->
            <input type="submit" value="find" name="submit_search" id="submit_search">
        </form>
    </div>
</div>

<div class="container_search">

    <div id="summary">
        <div id="summary_content">
            <h1>#<?php echo $_POST["input_search"] ?></h1>
            <p><?php echo  $number_of_results ?> posts</p>
        </div>
    </div>

    <div id="results">
        <div id="results_content">
            <?php
                foreach($results as $result)
                {
                    {
                        print '<div class="results_results" style="background-image: url(../assets/posts/' . $result["photo"] . ')">';
                        print '<form><input type="text" value="'. $result["photo"] . '"><input type="submit" id="submit_detail" name="submit_detail" value="detail"/></form></div>';
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