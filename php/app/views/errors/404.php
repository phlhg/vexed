<script>

    $(document).ready(function(){
        var code_bg = $("#code_bg");
        var output = "";
        for(i = 0; i < 50000; i++){ 
            output += getRandomChar()+' ';
        }
        $(code_bg).html(output);

        setInterval(function(){
            var output = "";
            for(i = 0; i < 50000; i++){ 
                output += getRandomChar()+' ';
            }
            $(code_bg).html(output);
            /*output = getRandomChar()+' '+output;
            output = output.substr(0,50000);
            $(code_bg).html(output);*/
        },200);
    });

    function getRandomChar(){
        return Math.round(Math.random());
    }

</script>
<style>
    @import url('https://fonts.googleapis.com/css?family=Inconsolata');

</style>
<section class="min-fullscreen">
    <div class="ph_bg_image" id="code_bg" style="font-family: 'Inconsolata', monospace; normal;opacity: 0.05;">
        
    </div>
    <div class="ph_text center">
        <h2 class="ph_head_deposited">404</h2>
        <h4>Die Seite existiert nicht oder wurde gelöscht.</h4>
        <p>Versuche die gewünschte Seite über das Menu oder über Start zu finden.</p>
        <a href="/" class="ph_button">Start</a>
<?php if($view->client->isloggedIn()){ ?><a class="ph_button">Profil</a><?php }?>
    </div>
</section>