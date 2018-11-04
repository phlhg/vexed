<section class="min-fullscreen">
    <div class="ph_text">
        <form action="" method="POST">
            <input name="ph_signup_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
            <div class="ph_form_input_submit">
                <input name="q" autofocus="" value="<?=$_var->query?>" type="text" placeholder="Entdecke Nutzer, Beiträge, Hashtags, ..."/>
                <span class="ph_fi_submit"><i class="material-icons">search</i></span>
            </div>
            <span class="ph_form_error"><?=(isset($_var->q_error) ? $_var->q_error : '')?></span>
            <span class="ph_form_info"></span>
            <input type="submit" value="Suchen"/>
        </form>
        <?php $res_c = count($_var->q_results); ?>
        <?php if($res_c > 0){ ?>
            <p style="margin: 0 0 20px 0"><?=($res_c == 1 ? "1 Ergebnis" : $res_c." Ergebnisse")?> für "<?=$_var->query?>":</p>
            <div class="ph_profile_list"><?php
                foreach($_var->q_results as $id){
                    $user = new \App\Models\Account\User($id);
                    echo $user->toHtml();
                }
            ?></div>
        <?php } else { ?>
            <p style="margin: 0 0 20px 0">Leider wurde nichts für "<?=$_var->query?>" gefunden</p>
        <?php } ?>
    </div>
</section>