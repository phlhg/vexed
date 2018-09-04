<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <div class="ph_progress_dots"><?php for($i = 0; $i < $view->v->signup_step_max; $i++){ ?><span <?=($i == $view->v->signup_step?'class="active" ':'')?>></span><?php } ?></div>
            <h1>Nutzername</h1>
            <p>Wähle einen Nutzernamen, welcher dir gefällt. Wir überprüfen dann, ob er noch verfügbar ist.</p>
            <form action="" method="POST">
                <input name="ph_signup_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
                <input autofocus="" name="ph_signup_username" required value="<?=$view->v->signup_form_username?>" type="text" placeholder="Nutzername"  pattern="([A-Za-z\._\-$]{3,25})" title="Gib einen gültigen Nutzernamen mit 3 bis 25 Zeichen ein." />
                <span <?php echo (empty($view->v->form_error) ? 'style="display: none;" ': ''); ?>class="ph_login_error"><i class="material-icons">error</i> <?=$view->v->form_error?></span>
                <span <?php echo (empty($view->v->form_info) ? 'style="display: none;" ': ''); ?>class="ph_login_info"><i class="material-icons">info</i> <?=$view->v->form_info?></span>
                <input type="submit" value="Weiter"/>
            </form>
            <span class="ph_text_reference">
                Nutzernamen unterliegen den <a href="/conditions/" target="_blank">Nutzungsbedingungen</a>.
            </span>
        </div>
    </div>
    <div class="ph_separator_half">
        <div class="ph_text">
            <i class="material-icons ph_big_icon">person_outline</i>
        </div>
    </div>
</section>