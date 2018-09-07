<?php

    $this->getView("page/header");
    if($this->meta->menu && $this->menu != "")
        $this->getView($this->menu);
    $this->getView($this->view);
    $this->getView("page/footer");

?>