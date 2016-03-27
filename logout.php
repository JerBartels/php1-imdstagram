<?php

//include_once("session.php");

//sessie verwijderen
session_destroy();

//redirect naar homepage
header('location: login.php');
?>