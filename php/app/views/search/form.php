<form action="" method="POST">
    <input name="ph_signup_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
    <div class="ph_search_suggestions">
        <div class="ph_form_input_submit">
            <input name="q" autofocus="" autocomplete="off" value="<?=$_var->query?>" type="text" placeholder="Entdecke Nutzer, Beiträge, Hashtags, ..."/>
            <span class="ph_fi_submit"><i class="material-icons">search</i></span>
        </div>
        <div class="ph_suggestions"></div>
    </div>
    <span class="ph_form_error"><?=(isset($_var->q_error) ? $_var->q_error : '')?></span>
    <span class="ph_form_info"></span>
    <input type="submit" value="Suchen"/>
</form>