<?php

session_start();

function alreadyLoggedIn()
{
    if(isset($_SESSION["username"]) && !empty($_SESSION["username"]))
    {
        header("location: pages/satchmo.php");
    }

}