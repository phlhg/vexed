<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <div class="ph_progress_dots"><?php for($i = 0; $i < $view->v->signup_step_max; $i++){ ?><span <?=($i == $view->v->signup_step?'class="active" ':'')?>></span><?php } ?></div>
            <h1>E-Mail</h1>
            <p>Um dich über Wichtiges zu informieren benötigen wir eine E-Mail-Adresse um dich zu kontaktieren.</p>
            <form action="" method="POST">
                <input name="ph_signup_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
                <input autofocus="" name="ph_signup_email" required value="<?=$view->v->signup_form_email?>" type="email" placeholder="E-Mail" title="Gib einen gültigen E-Mail an" />
                <span <?php echo (empty($view->v->form_error) ? 'style="display: none;" ': ''); ?>class="ph_login_error"><i class="material-icons">error</i> <?=$view->v->form_error?></span>
                <span <?php echo (empty($view->v->form_info) ? 'style="display: none;" ': ''); ?>class="ph_login_info"><i class="material-icons">info</i> <?=$view->v->form_info?></span>
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