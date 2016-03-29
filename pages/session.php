<?php

session_start();

//controlefunctie -> gebruiker al ingelogd?
function alreadyLoggedIn()
{
    if(isset($_SESSION["username"]) && !empty($_SESSION["username"]))
    {
        header("location: pages/satchmo.php");
    }
}