<?php if($view->meta->menu){ ?>
    <header>
        <nav><!--
            --><a href="/">Home</a><!--
            --><a href="/search/">Suchen</a><!--
            <?php if(isset($view->v->client->name)){ ?>
            --><a href="/profile/<?php echo $view->v->client->name; ?>/">Profil</a><!--
            <?php } ?>
        --></nav>
    </header>
<?php } ?>