<?php

include_once("../classes/Post.class.php");

$post = new Post();

$selected_post = $post->getPostByPhoto($_POST["id"]);

$likes = $selected_post['likes'];
$upd_likes = $likes + 1;

$post->saveLikes($upd_likes, $_POST["id"]);

echo json_encode($selected_post);