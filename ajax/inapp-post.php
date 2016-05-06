<?php

include_once("../classes/Post.class.php");
include_once("../classes/Inapp.class.php");
include_once("../classes/User.class.php");
include_once ("../pages/reglog.php");


$post = new Post();
$inapp = new Inapp();
$user = new User();


$selected_post = $post->getPostByPhoto($_POST["current_id"]);
$selected_user = $user->getUserByUsername($_SESSION["username"]);


$inapp->Username = $selected_user["username"];
$inapp->Picture = $selected_post["photo"];


$inapps = $selected_post["inapp"];


if(!$inapp->AlreadyInapped($inapp->Username, $inapp->Picture))
{

    $inapp->Save();

    $upd_inapps = intval($inapps) + 1;
    $selected_post["inapp"] = $upd_inapps;
    $post->saveInapp($upd_inapps, $_POST["current_id"]);

    echo json_encode($selected_post);
}

else
{

    $inapp->DeleteInapp($inapp->Username, $inapp->Picture);

    $upd_inapps = intval($inapps) - 1;
    $selected_post["inapp"] = $upd_inapps;
    $post->saveInapp($upd_inapps, $_POST["current_id"]);

    echo json_encode($selected_post);
}
