<?php 
    require_once $_SERVER["DOCUMENT_ROOT"]."php/inc.php";
    require_once $_SERVER["DOCUMENT_ROOT"]."php/db.php";

    Core\Router::set("/","Home/index");
    Core\Router::set("/p/{username}/","Profile/index")->where(["username" => 'nickname']);
    Core\Router::set("/signup/","Home/signup");

    echo Core\Router::run($_SERVER["REQUEST_URI"]);
?>