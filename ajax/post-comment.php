<?php

include_once "../pages/init.php";
include_once "../pages/reglog.php";

$post = new Post();
$user = new User();
$comment =  new Comment();

$selected_post = $post->getPostById($_POST["current_post_id"]);
$selected_user = $user->getUserByUsername($_SESSION["username"]);
$actual_time = date('Y-m-d H:i:s', time());

$comment->Username = $selected_user["username"];
$comment->Post = $selected_post["id"];
$comment->Comment = strip_tags($_POST["comment"]);
$comment->Date = $actual_time;
$comment->Save();

$return_comment = $comment->getCommentByParam($selected_user["username"], $actual_time);

echo json_encode($return_comment);
