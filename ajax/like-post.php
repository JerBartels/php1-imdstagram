<?php

include_once("../classes/Post.class.php");
include_once("../classes/Likes.class.php");
include_once("../classes/User.class.php");
include_once ("../pages/reglog.php");


$post = new Post();
$like = new Likes();
$user = new User();


$selected_post = $post->getPostByPhoto($_POST["current_id"]);
$selected_user = $user->getUserByUsername($_SESSION["username"]);


$like->Username = $selected_user["username"];
$like->Picture = $selected_post["photo"];


$likes = $selected_post["likes"];


if(!$like->AlreadyLiked($like->Username, $like->Picture)){

    $like->Save();

    $upd_likes = intval($likes) + 1;
    $selected_post["likes"] = $upd_likes;
    $post->saveLikes($upd_likes, $_POST["current_id"]);

    echo json_encode($selected_post);
}

else{

    $like->DeleteLike($like->Username, $like->Picture);

    $upd_likes = intval($likes) - 1;
    $selected_post["likes"] = $upd_likes;
    $post->saveLikes($upd_likes, $_POST["current_id"]);

    echo json_encode($selected_post);
}





