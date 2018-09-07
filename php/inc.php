<?php
    session_start();

    //ENABLE GZIP ENDCODING
    ini_set('zlib.output_compression', 1);

    spl_autoload_register(function($classname){
        $file = $_SERVER["DOCUMENT_ROOT"]."php/".str_ireplace("\\","/",strtolower($classname)).".php";
        if(is_readable($file)){ require_once $file; }
    });

    require_once $_SERVER["DOCUMENT_ROOT"]."php/functions.php";
    require_once $_SERVER["DOCUMENT_ROOT"]."php/db.php";
?>