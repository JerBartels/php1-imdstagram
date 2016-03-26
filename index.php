<?php

spl_autoload_register(function ($class){
   include 'classes/' . $class . '.class.php';
});

?>

<!doctype html>

<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title>Satchmo</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="styles/reset.css">
        <link rel="stylesheet" href="styles/style.css">
    </head>

    <body>

    </body>

</html>