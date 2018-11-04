<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <div class="ph_progress_dots"><?php for($i = 0; $i < $view->v->signup_step_max; $i++){ ?><span <?=($i == $view->v->signup_step?'class="active" ':'')?>></span><?php } ?></div>
            <h2 class="ph_mobile_hide">Passwort</h2>
            <p>Wähle ein starkes Passwort um die Sicherheit deines Account zu gewährleisten</p>
            <form action="" method="POST">
                <input name="ph_signup_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
                <input name="username" type="text" value="<?=$_var->signup_form_username?>" style="display: none;" />
                <div class="ph_pwc"><input id="mainpw" autofocus="autofocus" name="ph_signup_password" autocomplete="off" required value="" type="password" placeholder="Passwort" title="Wähle ein möglichst starkes Passwort" /></div>
                <div class="ph_form_input_submit">
                    <div class="ph_pwc"><input data-check="mainpw" name="ph_signup_password_confirm" autocomplete="off" required value="" type="password" placeholder="Passwort bestätigen" title="Bestätige dein passwort" /></div>
                    <span class="ph_fi_submit"><i class="material-icons">chevron_right</i></span>
                </div>
                <span class="ph_form_error"><?=(isset($view->v->form_error) ? $view->v->form_error : '')?></span>
                <span class="ph_form_info"><?=(isset($view->v->form_info) ? $view->v->form_info : '')?></span>
                <input type="submit" value="Weiter"/>
            </form>
            <span class="ph_text_reference">
                Das Passwort sollte enthalten:<br/>
                - <strong>Gross- & Kleinbuchstaben</strong><br/>
                - <strong>Zahlen</strong><br/>
                - <strong>Spezialzeichen</strong><br/>
                - <strong>Mindestens 5 Zeichen</strong>
            </span>
        </div>
    </div>
    <div class="ph_mobile_hide ph_separator_half">
        <div class="ph_text">
            <i class="material-icons ph_big_icon">security</i>
        </div>
    </div>
</section>