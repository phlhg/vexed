<section class="fullscreen ph_separator">
    <div class="ph_separator_half">
        <div class="ph_text">
            <i class="material-icons ph_big_icon" style="color: rgb(0,255,0);">check</i>
        </div>
    </div>
    <div class="ph_separator_half">
        <div class="ph_text">
            <h2>Bestätigt</h2>
            <p>Deine Account wurde bestätigt - Du kannst dich jetzt anmelden.</p>
            <?php if($view->client->isLoggedIn()){ ?>
                <a href="/logout/" class="ph_button main">Anmelden</a>
            <?php } else { ?>
                <a href="/" class="ph_button main">Anmelden</a>
            <?php } ?>
        </div>
    </div>
</section>