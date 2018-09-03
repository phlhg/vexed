<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <h1>Nutzername</h1>
            <p>Wähle einen Nutzernamen, welcher dir gefällt. Wir überprüfen dann, ob er noch verfügbar ist.</p>
            <form action="" method="POST">
                <input name="ph_signup_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
                <input autofocus="" name="ph_signup_username" required value="<?=$view->v->signup_form_username?>" type="text" placeholder="Nutzername"  pattern="([A-Za-z\._\-$]{3,25})" title="Gib einen gültigen Nutzernamen mit 3 bis 25 Zeichen ein." />
                <?php if(!empty($view->v->login_form_error)){ ?>
                    <span class="ph_signup_error"><i class="material-icons">error</i> <?php echo $view->v->login_form_error; ?></span>
                <?php } ?>
                <?php if(!empty($view->v->login_form_info)){ ?>
                    <span class="ph_signup_info"><i class="material-icons">info</i> <?php echo $view->v->login_form_info; ?></span>
                <?php } ?>
                <input type="submit" value="Weiter"/>
            </form>
            <span class="ph_text_reference">
                Nutzernamen unterliegen den <a href="/conditions/" target="_blank">Nutzungsbedingungen</a> und
                dürfen unter anderem keinen beleidigenden, rassistischen, pornografischen, gewaltverherrlichended oder irreführenden Inhalt haben.
                Nutzer welche diese Bestimmungen nicht befolgen werden unwiederufbar gesperrt.
            </span>
        </div>
    </div>
    <div class="ph_separator_half">
        <div class="ph_text">
            <i class="material-icons ph_big_icon">person_outline</i>
        </div>
    </div>
</section>