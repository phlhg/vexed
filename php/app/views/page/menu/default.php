<!--MENU-->
<header><!--
    --><a href="/" class="ph_logo_wrapper">
        <img src="/img/icons/favicon.png" /><?php echo \Core\Config::get("application_title"); ?>
    </a><!--
    <?php if($view->client->isLoggedIn()){ ?>
        --><nav><!--
            --><a href="/search/">Suchen</a><!--
            --><a href="/profile/<?php echo $view->client->name; ?>/">Profil</a><!--
        --></nav><!--
        --><a href="/p/<?=$view->client->name?>" class="ph_profile">
            <span>phlhg</span><div class="ph_profile_pb"></div>
        </a><!--
    <?php } else { ?>
        --><nav><!--
            --><a href="/about/">Über</a><!--
            --><a href="/signup/">Registrieren</a><!--
        --></nav><!--
        --><a href="/" class="ph_profile sign_in">
            <span>Login</span><div class="ph_profile_pb"><i class="material-icons">chevron_right</i></div>
        </a><!--
    <?php } ?>
--></header>