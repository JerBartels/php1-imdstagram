<?php

include_once("../classes/Comment.class.php");
include_once("../classes/User.class.php");
include_once("../classes/Post.class.php");

$comment = new Comment();

if(!empty($_POST['input_comment_form']))
{
    $comment->Comment = $_POST['input_comment_form'];
    $comment->User = $_POST['comment_by'];
    $comment->Post = $_POST['comment_on'];
    $comment->Date = date('Y-m-d H:i:s', time());

    try
    {
        $comment->Save();
        echo "success";
    }
    catch(Exception $e)
    {
        echo $e;
    }
}