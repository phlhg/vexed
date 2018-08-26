<section class="min-fullscreen">
    <div class="ph_text">
        <h2>Hoppla!</h2>
        <p>
            Leider ist hier etwas unerwartet schief gelaufen...<br/>
            Um dies in Zukunft zu vermeiden wurde der Fehler gemeldet.<BR/>
            <BR/>
            Versuche es in einigen Minuten noch einmal<BR/>
            <BR/>
            Fehlercode<br/>
            <a href="/faq/errors/code/<?php echo $view->v->error_details["code"]; ?>/"><strong><?php echo $view->v->error_details["code"]; ?></strong></a>
        </p>
        <script type="text/javascript">
                var error = <?php echo json_encode($view->v->error_details); ?>;
                console.log("PHP | "+error.dev + ' ['+error.file+":"+error.line+"]");
                console.log(error.code);
        </script>
    </div>
</section>