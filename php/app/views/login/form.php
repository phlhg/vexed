<form action="" method="POST">
    <input name="ph_login_username" type="text" placeholder="Nutzername oder E-Mail-Adresse"/>
    <input name="ph_login_password" type="password" placeholder="Passwort" />
    <input type="submit" value="Anmelden"/>
    <!--<label style="float: right;"><input name="ph_login_preserve" type="checkbox" /> Angemeldet bleiben</label>-->
    <?php if(!empty($view->v->login_form_msg)){ ?><span class="ph_login_message"><?php echo $view->v->login_form_msg; ?></span><?php } ?>
</form>