<?php

include_once("../classes/Post.class.php");

$post = new Post();

$posts =array_reverse($post->getAllPosts());

echo json_encode($posts);



