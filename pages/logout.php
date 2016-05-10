<?php

include_once("init.php");

//sessie verwijderen
session_destroy();

//redirect naar homepage
header('location: ../index.php');
?>