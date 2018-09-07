<?php 
    require_once $_SERVER["DOCUMENT_ROOT"]."php/inc.php";

    $app = new Core\App();
    echo $app->render();
?>