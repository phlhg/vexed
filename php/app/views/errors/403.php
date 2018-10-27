<section class="min-fullscreen">
    <div class="ph_background_text">403</div>
    <div class="ph_text">
        <h2>Keine Berechtigung</h2>
        <p>Leider hast du keine Berechtigung um auf diese Seite zugreifen zu können.</p>
        <a href="/" class="ph_button main">Home</a>
        <?php if($view->client->isloggedIn()){ ?>
            <a href="/p/<?=$view->client->name?>" class="ph_button">Profil</a>
        <?php } else { ?>
            <a href="/" class="ph_button">Anmelden</a>
            <a href="/signup/" class="ph_button">Registrieren</a>
        <?php } ?>
    </div>
</section>