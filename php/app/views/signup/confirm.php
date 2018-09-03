<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <h1>Bestätigen</h1>
            <p>Bitte bestätige deinen Account mit dem an dich gesendeten Sicherheitscode. Klicke danach auf "Starten".</p>
            <form action="/" method="POST">
                <input name="ph_login_tkn" type="hidden" value="<?=\Helpers\Token::get()?>" />
                <input type="hidden" name="ph_login_username" value="<?=$view->v->signup_form_username?>" />
                <input type="hidden" name="ph_login_password" value="<?=$view->v->signup_form_password?>" />
                <input type="submit" value="Starten!"/>
            </form>
        </div>
    </div>
    <div class="ph_separator_half">
        <div class="ph_text">
            <i class="material-icons ph_big_icon" style="color: rgb(0,150,50);">done</i>
        </div>
    </div>
</section>