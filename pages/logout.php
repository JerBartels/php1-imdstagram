<?php

include_once "init.php";

//specific pages
include_once "session.php";
include_once "reglog.php";

//sessie verwijderen
session_destroy();

//redirect naar homepage
header('location: ../index.php');
?>