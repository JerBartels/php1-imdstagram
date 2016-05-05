<?php

    session_start();

    require_once( '../Facebook/autoload.php' );

    $fb = new Facebook\Facebook
    ([
        'app_id' => '466986893500706',
        'app_secret' => '44783cc0ee1fddb7f3808aa86519fd6b',
        'default_graph_version' => 'v2.5',
    ]);

    $helper = $fb->getRedirectLoginHelper();

    $permissions = ['email'];
    $loginUrl = $helper->getLoginUrl('http://localhost:8888/php1-satchmo/pages/fb-callback.php', $permissions);

    header("location: ".$loginUrl);

?>