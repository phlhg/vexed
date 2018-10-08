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

?>