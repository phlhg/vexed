<?php

    $this->getView("core/html/header");
    if($this->meta->menu && $this->menu != "")
        $this->getView($this->menu);
    $this->getView($this->view);
    $this->getView("core/html/footer");

?>