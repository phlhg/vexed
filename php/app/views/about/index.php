<section style="overflow: visible;" class="halfscreen transparent-menu">
    <div class="ph_profile_bg"><div style="filter: blur(0); background-position: center bottom; background-image: url(http://phlhg.ch/webdev/projects/vxd.png);"></div></div>
    <div class="ph_profile_bg_overlay"></div>
    <div class="ph_profile_bg_easing"></div>
</section>
<section class="min-halfscreen ph_afterphead">
    <div class="ph_text">
        <h1>Über</h1>
        <p class="justify">
            VEXED entsteht im Rahmen meiner Maturitätsarbeit am <a target="_blank" href="http://mng.ch">MNG Rämibühl</a> im Fach Informatik. 
            Das Ziel ist es ein einfaches soziales Netzwerk zu entwickeln, 
            in welchem sich Nutzer mit Text & Bildern austauschen können und diese bewerten und kommentieren können.
        </p>
        <h3>Beitreten?</h3>
        <p class="justify">
            Falls du beitreten möchtest, kannst du dich einfach bei mir melden (Persönlich, Whatsapp, <a href="http://phlhg.ch/contact/getintouch/">phlhg.ch</a> etc.).
            Danach erhälst du (wenn möglich) einen Aktivierungscode um dich zu <a href="/signup/">registrieren</a>.
        </p>
        <h3>Abgabe</h3>
        <p class="justify">
            Der Abgabe-Termin ist der 7. Januar 2019.
            <?php 
                $termin = 1546866000;
                $d = round(($termin-time())/(60*60*24),0);
            ?><br/>
            <?php if($d > 0){ ?>
                Es verbleiben noch <strong><?=$d?> Tage</strong> bis zur Abgabe.
            <?php } else { ?>
                <?php if($d == 0){ ?>
                    Die Arbeit wurde <strong>Heute</strong> abgegeben.
                <?php } ?>
            <?php } ?>
        </p>
        <?php if($d < 1){ ?>
            <h3>Bewertung</h3>
            <p class="justify">
                tba
            </p>
        <?php } ?>
    </div>
</section>