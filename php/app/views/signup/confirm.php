<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <div class="ph_progress_dots"><?php for($i = 0; $i < $view->v->signup_step_max; $i++){ ?><span <?=($i == $view->v->signup_step?'class="active" ':'')?>></span><?php } ?></div>
            <h2 class="ph_mobile_hide">Bestätigen</h2>
            <p>Bitte bestätige deinen Account mit dem an dich gesendeten Bestätigungs-Link. Klicke danach auf "Starten".</p>
            <form action="/" method="POST">
                <input name="ph_login_tkn" type="hidden" value="<?=\Helpers\Token::get()?>" />
                <input type="hidden" name="ph_login_username" value="<?=$view->v->signup_form_username?>" />
                <input type="hidden" name="ph_login_password" value="<?=$view->v->signup_form_password?>" />
                <span class="ph_inline_submit">Login <i class="material-icons">chevron_right</i></span>
                <input type="submit" value="Starten!"/>
            </form>
        </div>
    </div>
    <div class="ph_separator_half">
        <div class="ph_text">
            <i class="material-icons ph_big_icon">check_circle_outline</i>
        </div>
    </div>
</section>