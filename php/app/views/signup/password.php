<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <div class="ph_progress_dots"><?php for($i = 0; $i < $view->v->signup_step_max; $i++){ ?><span <?=($i == $view->v->signup_step?'class="active" ':'')?>></span><?php } ?></div>
            <h1>Passwort</h1>
            <p>Wähle ein starkes Passwort um die Sicherheit deines Account zu gewährleisten</p>
            <form action="" method="POST">
                <input name="ph_signup_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
                <input autofocus="" name="ph_signup_password" autocomplete="off" required value="" type="password" placeholder="Passwort" title="Wähle ein möglichst starkes Passwort" />
                <input name="ph_signup_password_confirm" autocomplete="off" required value="" type="password" placeholder="Passwort bestätigen" title="Bestätige dein passwort" />
                <?php if(!empty($view->v->login_form_error)){ ?>
                    <span class="ph_signup_error"><i class="material-icons">error</i> <?php echo $view->v->login_form_error; ?></span>
                <?php } ?>
                <?php if(!empty($view->v->login_form_info)){ ?>
                    <span class="ph_signup_info"><i class="material-icons">info</i> <?php echo $view->v->login_form_info; ?></span>
                <?php } ?>
                <input type="submit" value="Weiter"/>
            </form>
        </div>
    </div>
    <div class="ph_separator_half">
        <div class="ph_text">
            <i class="material-icons ph_big_icon">security</i>
        </div>
    </div>
</section>