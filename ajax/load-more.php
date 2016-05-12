<?php

include_once "../pages/init.php";
include_once "../pages/reglog.php";

$post = new Post();

$number_of_clicks = intval($_POST["number_of_clicks"]);

$start = $number_of_clicks * 5;
$stop = $start + 5;

$posts = $post->getPosts($start, $stop);

echo json_encode($posts);


