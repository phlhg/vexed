<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <img src="/img/grafiken/exept.png" class="ph_fun_graphics"/>
        </div>
    </div>
    <div class="ph_separator_half">
        <div class="ph_text">
            <h2>Whoops!</h2>
            <p>
                Leider ist hier etwas unerwartet schief gelaufen...<br/>
                Um dies in Zukunft zu vermeiden wurde der Fehler gemeldet.
            </p>
            <a href="/" class="ph_button main">Start</a>
            <?php if(__CLIENT()->isLoggedIn()){ ?>
                <a href="/p/<?=__CLIENT()->name?>/" class="ph_button">Profil</a>
            <?php } ?>
            <p>
                <br/>
                Fehlercode<br/>
                <strong><?php echo $view->v->error_details["code"]; ?></strong>
            </p>
        </div>
    </div>
</section>