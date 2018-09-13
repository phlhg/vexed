<section class="min-fullscreen">
    <div class="ph_background_text">404</div>
    <div class="ph_text">
        <h2>Die Seite wurde nicht gefunden</h2>
        <p>Möglicherweise ist sie in einem schwarzen Loch verschwunden oder hat das Multiversum gewechselt.</p>
        <a href="/" class="ph_button main">Home</a>
        <?php if($view->client->isloggedIn()){ ?>
            <a href="/p/<?=$view->client->name?>" class="ph_button">Profil</a>
        <?php } else { ?>
            <a href="/" class="ph_button">Anmelden</a>
            <a href="/signup/" class="ph_button">Registrieren</a>
        <?php } ?>
    </div>
</section>