<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <div class="ph_progress_dots"><?php for($i = 0; $i < $view->v->signup_step_max; $i++){ ?><span <?=($i == $view->v->signup_step?'class="active" ':'')?>></span><?php } ?></div>
            <h1>Aktivierungs<wbr/>code*</h1>
            <p>Bitte gib deinen Code ein oder scanne deinen QR-Code.</p>
            <form action="" method="POST">
                <input name="ph_signup_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
                <input name="ph_signup_actcode" autocomplete="off" autofocus="" onkeyup="structureCode(event,this,3,3)" required value="<?=$view->v->signup_form_actcode?>" type="text" placeholder="z.B A1B 2C3 D4E"  title="Aktivierungscodes haben das Format A1B 2C3 D4E" />
                <?php if(!empty($view->v->signup_form_error)){ ?>
                    <span class="ph_login_error"><i class="material-icons">error</i> <?php echo $view->v->signup_form_error; ?></span>
                <?php } ?>
                <?php if(!empty($view->v->signup_form_info)){ ?>
                    <span class="ph_login_info"><i class="material-icons">info</i> <?php echo $view->v->signup_form_info; ?></span>
                <?php } ?>
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