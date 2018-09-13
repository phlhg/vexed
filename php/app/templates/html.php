<?php

    $this->getView("core/html/header");
    $this->getView("core/html/menu/default");
    $this->getView($this->view);
    $this->getView("core/html/footer");

?>