<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <h2>Aktivierung</h2>
            <p>Bitte gib deinen Code ein oder scanne deinen QR-Code.</p>
            <form action="" method="POST">
                <input name="ph_signup_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
                <div class="ph_form_input_submit">
                    <input name="ph_signup_actcode" autocomplete="off" autofocus="autofocus" onkeyup="structureCode(event,this,3,3)" required value="<?=$view->v->signup_form_actcode?>" type="text" placeholder="z.B A1B 2C3 D4E"  title="Aktivierungscodes haben das Format A1B 2C3 D4E" />
                    <span class="ph_fi_submit"><i class="material-icons">chevron_right</i></span>
                </div>
                <span class="ph_form_error"><?=(isset($view->v->form_error) ? $view->v->form_error : '')?></span>
                <span class="ph_form_info"><?=(isset($view->v->form_info) ? $view->v->form_info : '')?></span>
                <input type="submit" value="Weiter"/>
            </form>
            <span class="ph_text_reference">* Aktivierungscodes sind beim Entwickler erhältlich.</span>
        </div>
    </div>
    <div class="ph_separator_half">
        <div class="ph_text">
            <i class="material-icons ph_big_icon">lock_open</i>
        </div>
    </div>
</section>