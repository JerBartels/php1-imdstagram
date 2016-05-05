<?php

    session_start();

    require_once('../Facebook/autoload.php');
    require_once('../classes/Db.class.php');
    require_once('../classes/User.class.php');

    $fb = new Facebook\Facebook
    ([
        'app_id' => '466986893500706',
        'app_secret' => '44783cc0ee1fddb7f3808aa86519fd6b',
        'default_graph_version' => 'v2.5',
    ]);

    $helper = $fb->getRedirectLoginHelper();

    try
    {
        $accessToken = $helper->getAccessToken();
    }
    catch(Facebook\Exceptions\FacebookResponseException $e)
    {
        // When Graph returns an error
        echo 'Graph returned an error: ' . $e->getMessage();
        exit;
    }
    catch(Facebook\Exceptions\FacebookSDKException $e)
    {
        // When validation fails or other local issues
        echo 'Facebook SDK returned an error: ' . $e->getMessage();
        exit;
    }

    try
    {
        // Get the Facebook\GraphNodes\GraphUser object for the current user.
        // If you provided a 'default_access_token', the '{access-token}' is optional.
        $response = $fb->get('/me?fields=id,name,email,first_name,last_name', $accessToken->getValue());
    }
    catch(Facebook\Exceptions\FacebookResponseException $e)
    {
        // When Graph returns an error
        echo 'ERROR: Graph ' . $e->getMessage();
        exit;
    }
    catch(Facebook\Exceptions\FacebookSDKException $e)
    {
        // When validation fails or other local issues
        echo 'ERROR: validation fails ' . $e->getMessage();
        exit;
    }

    $me = $response->getGraphUser();

    $email = $me->getProperty("email");
    $username = strtolower($me->getProperty("first_name")) . "_" . strtolower($me->getProperty("last_name"));

    $user = new User();

    if(!$user->Exists($username, "username") && !$user->Exists($email, "email"))
    {
        try {
            $user->Username = $me->getProperty("first_name") . "_" . $me->getProperty("last_name");
            $user->Firstname = $me->getProperty('first_name');
            $user->Lastname = $me->getProperty('last_name');
            $user->Email = $me->getProperty("email");
            $user->ProfilePic = "noprofilepict.jpg";
            $user->Private = false;

            //user bewaren in DB
            $user->Save();

            //sessie aanmaken zodat tijdens zelfde sessie niet opnieuw ingelogd moet worden
            $_SESSION["username"] = $user->Username;

            //redirect naar applicatie
            header("location: satchmo.php");
        }
        catch (Exception $e)
        {
            header("location: ../index.php");
            echo $e->getMessage();
        }
    }
    else
    {
        try
        {
            $db_user = $user->getUserByUsername($username);
            $db_email = $db_user["email"];
            $db_username = $db_user["username"];

            if($db_email == $email && $db_username == $username)
            {

                //gebruiker bestaat in DB en combi username/email is juist dus we mogen alvast de username koppelen aan de gebruiker
                $user->Username = $username;

                //ook hier starten we de sessie
                $_SESSION["username"] = $user->Username;

                //doorverwijzen naar de application pagina
                header("location: satchmo.php");
            }
            else
            {
                echo "something went wrong, please contact us";
            }
        }
        catch(Exception $e)
        {
            echo $e->getMessage();
        }
    }

?>