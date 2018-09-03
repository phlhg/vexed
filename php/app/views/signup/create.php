<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <h1>Erstellen</h1>
            <p>Nachdem dein Account erstellt wurde, wirst du eine E-Mail mit einem Sicherheitscode erhalten um den Account zu bestätigen</p>
            <form action="" method="POST">
                <input name="ph_signup_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
                <label><input autofocus="" type="checkbox" name="ph_signup_cond" required /> Ich akzeptiere die <a href="/conditions/">allgemeinen Nutzungsbedingungen</a></label>
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