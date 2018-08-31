<!--MENU-->
<header>
    <a href="/" class="ph_logo_wrapper">
        <img src="/img/icons/favicon.png" /><?php echo \Core\Config::get("application_title"); ?>
    </a>
    <?php if($view->client->isLoggedIn()){ ?>
        <nav><!--
            --><a href="/search/">Suchen</a><!--
            --><a href="/profile/<?php echo $view->client->name; ?>/">Profil</a><!--
        --></nav>
    <?php } else { ?>
        <nav><!--
            --><a href="/about/">Über</a><!--
            --><a href="/signup/">Registrieren</a><!--
            --><a href="/login/">Login</a><!--
        --></nav>
    <?php } ?>
</header>