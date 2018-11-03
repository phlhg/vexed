<?php

    App::$router->set("/","Home/index");

    //LOGIN
    App::$router->set("/login/","Login/index");
    App::$router->set("/logout/","Login/logout");

    //SIGNUP
    App::$router->set("/signup/","Signup/index");
    App::$router->set("/signup/confirm/{id}/{seccode}/","Signup/confirm")->where(["seccode" => '[A-Z0-9]+','id' => 'int']);
    App::$router->set("/signup/{actcode}/{step}/","Signup/advanced")->where(["actcode" => '[A-Z0-9]+','step' => 'int']);

    //PROFILE
    App::$router->set("/p/{username}/","Profile/index")->where(["username" => 'nickname']);
    App::$router->set("/p/{username}/{site}/","Profile/_switch")->where(["username" => 'nickname']);
    App::$router->set("/img/pb/{id}/","Profile/pb")->where(["id" => "int"]);
    App::$router->set("/img/pbg/{id}/","Profile/pbg")->where(["id" => "int"]);

    //POST
    App::$router->set("/create/","CreatePost/index");

    //AJAX
    App::$router->set("/ajax/f/{function}/","Ajax/_switch");

    //ABOUT
    App::$router->set("/about/","etc/about");

    //SEARCH
    App::$router->set("/search/","Search/index");
    App::$router->set("/search/{query}/","Search/query");

?>