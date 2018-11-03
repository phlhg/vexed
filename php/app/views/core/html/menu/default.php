<!--MENU-->
<header class="<?=(isset($view->v->page_menu_class) ? $view->v->page_menu_class : '')?>"><div class="ph_inner"><!--
    --><div class="ph_mham"><!--
        --><span></span><span></span><span></span><span></span><span></span><!--
    --></div><!--
    --><div class="ph_menur">
        <a href="/search/" class="ph_menu_point msearch"><i class="material-icons">search</i></a>
        <?php if($view->client->isLoggedIn()){ ?>
            <a href="/create/" class="ph_menu_point mcreate"><i class="material-icons">add</i></a>
            <a href="/p/<?=$view->client->name?>" class="ph_menu_point mprofile"><img src="/img/pb/<?=$view->client->id?>" /></a>
        <?php } else { ?>
            <a href="/" class="ph_menu_point mprofile no_user"><i class="material-icons">person_outline</i></a>
        <?php } ?>
        <a href="/" class="ph_logo_wrapper">
            <img src="/img/icons/logo.svg" />
        </a>
    </div><!--
--></div></header>
<?php $view->get("core/html/menu/nav"); ?>