<?php if($view->meta->menu){ ?>
    <header>
        <nav><!--
            --><a href="/">Home</a><!--
            --><a href="/search/">Suchen</a><!--
            <?php if($view->client->loggedIn()){ ?>
                --><a href="/profile/<?php echo $view->client->name; ?>/">Profil</a><!--
            <?php } ?>
        --></nav>
    </header>
<?php } ?>