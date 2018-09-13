<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <div class="ph_progress_dots"><?php for($i = 0; $i < $view->v->signup_step_max; $i++){ ?><span <?=($i == $view->v->signup_step?'class="active" ':'')?>></span><?php } ?></div>
            <h1>Erstellen</h1>
            <p>Nachdem dein Account erstellt wurde, wirst du eine E-Mail mit einem Bestätigungslink erhalten.</p>
            <form action="" method="POST">
                <input name="ph_signup_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
                <label><input autofocus="" type="checkbox" name="ph_signup_cond" required /> Ich akzeptiere die <a href="/conditions/">allgemeinen Nutzungsbedingungen</a></label>
                <span class="ph_form_error"><?=(isset($view->v->form_error) ? $view->v->form_error : '')?></span>
                <span class="ph_form_info"><?=(isset($view->v->form_info) ? $view->v->form_info : '')?></span>
                <span class="ph_inline_submit">Let's go! <i class="material-icons">chevron_right</i></span>
                <input type="submit" value="Erstellen"/>
            </form>
        </div>
    </div>
    <div class="ph_separator_half">
        <div class="ph_text">
            <i class="material-icons ph_big_icon">person_add</i>
        </div>
    </div>
</section>