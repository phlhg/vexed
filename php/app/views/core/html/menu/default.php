<!--MENU-->
<header class="<?=(isset($view->v->page_menu_class) ? $view->v->page_menu_class : '')?>"><div class="ph_inner"><!--
    --><a href="/" class="ph_logo_wrapper">
            <img src="/img/icons/logo.svg" />
    </a><!--
    --><div class="ph_menur">
        <?php if($view->client->isLoggedIn()){ ?>
            <a title="Start" href="/" class="ph_menu_point mhome"><i class="material-icons">home</i></a>
            <a title="Entdecken" href="/search/" class="ph_menu_point msearch"><i class="material-icons">search</i></a>
            <a title="Beitrag erstellen" href="/create/" class="ph_menu_point mcreate"><i class="material-icons">add</i></a>
            <a title="Profil" href="/p/<?=$view->client->name?>" class="ph_menu_point mprofile"><img src="/img/pb/<?=$view->client->id?>" /></a>
            <!--<a title="Abmelden" href="/logout/?re=<?=\App::$router->url?>" class="ph_menu_point mlogout"><i class="material-icons">power_settings_new</i></a>-->
        <?php } else { ?>
            <a href="/" class="ph_menu_point mprofile no_user"><i class="material-icons">person_outline</i></a>
        <?php } ?>
    </div><!--
--></div></header>
<div class="mobile_header <?=(isset($view->v->page_menu_class) ? $view->v->page_menu_class : '')?>">
    <a href="/" class="ph_logo_wrapper">
        <img src="/img/icons/logo.svg" />
    </a>
</div>