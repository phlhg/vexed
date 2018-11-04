<form action="" method="POST">
    <input name="ph_login_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
    <input name="ph_login_username" required autocorrect="off" autocapitalize="none" value="<?php echo $view->v->login_used_username; ?>" type="text" placeholder="Nutzername oder E-Mail"  pattern="([A-Za-z\._\-$]{3,25})|([a-z\.]+@[a-z]+\.[a-z]{1,4})" title="Gib eine gültige E-Mail oder eine gültigen Nutzernamen mit 3 bis 25 Zeichen ein." />
    <div class="ph_form_input_submit">
        <input name="ph_login_password" required value="<?php echo $view->v->login_used_password; ?>" type="password" placeholder="Passwort" />
        <span class="ph_fi_submit"><i class="material-icons">chevron_right</i></span>
    </div>
    <span class="ph_form_error"><?=(isset($view->v->login_form_error) ? $view->v->login_form_error : '')?></span>
    <span class="ph_form_info"><?=(isset($view->v->login_form_info) ? $view->v->login_form_info : '')?></span>
    <input type="submit" value="Anmelden"/>
    <label class="check inline" style="float: right;"><input <?php echo ($view->v->login_used_remember ? 'checked="" ' : ''); ?>name="ph_login_preserve" type="checkbox" /> Angemeldet bleiben</label>
</form>