<form action="" method="POST">
    <input name="ph_login_tkn" type="hidden" value="<?php echo \Core\Helpers\Token::get(); ?>" />
    <input name="ph_login_username" required value="<?php echo $view->v->login_used_username; ?>" type="text" placeholder="Nutzername oder E-Mail"  pattern="([A-Za-z\._\-$]{3,25})|([a-z\.]+@[a-z]+\.[a-z]{1,4})" title="Gib eine gültige E-Mail oder eine gültigen Nutzernamen mit 3 bis 25 Zeichen ein." />
    <input name="ph_login_password" required value="<?php echo $view->v->login_used_password; ?>" type="password" placeholder="Passwort" />
    <?php if(!empty($view->v->login_form_error)){ ?>
        <span class="ph_login_error"><i class="material-icons">error</i> <?php echo $view->v->login_form_error; ?></span>
    <?php } ?>
    <?php if(!empty($view->v->login_form_info)){ ?>
        <span class="ph_login_info"><i class="material-icons">info</i> <?php echo $view->v->login_form_info; ?></span>
    <?php } ?>
    <input type="submit" value="Anmelden"/>
    <label style="float: right;"><input <?php echo ($view->v->login_used_remember ? 'checked="" ' : ''); ?>name="ph_login_preserve" type="checkbox" /> Angemeldet bleiben</label>
</form>