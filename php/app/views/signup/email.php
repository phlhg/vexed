<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <h1>E-Mail</h1>
            <p>Um dich über Wichtiges zu informieren benötigen wir eine E-Mail-Adresse um dich zu kontaktieren.</p>
            <form action="" method="POST">
                <input name="ph_signup_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
                <input autofocus="" name="ph_signup_email" required value="<?=$view->v->signup_form_email?>" type="email" placeholder="E-Mail" title="Gib einen gültigen E-Mail an" />
                <?php if(!empty($view->v->login_form_error)){ ?>
                    <span class="ph_signup_error"><i class="material-icons">error</i> <?php echo $view->v->login_form_error; ?></span>
                <?php } ?>
                <?php if(!empty($view->v->login_form_info)){ ?>
                    <span class="ph_signup_info"><i class="material-icons">info</i> <?php echo $view->v->login_form_info; ?></span>
                <?php } ?>
                <input type="submit" value="Weiter"/>
            </form>
            <span class="ph_text_reference">
                Deine E-Mail-Adresse wird vertraulich behandelt und nicht an Dritte weitergegeben.
                Standartmässig wirst du nur über Relevantes (Account gesperrt, E-Mail geändert / bestätigen, Passwort vergessen, Passwort geändert) informiert.
            </span>
        </div>
    </div>
    <div class="ph_separator_half">
        <div class="ph_text">
            <i class="material-icons ph_big_icon">mail_outline</i>
        </div>
    </div>
</section>