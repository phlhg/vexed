<section class="min-fullscreen">
    <div class="ph_text">
        <?=$view->get("search/form")?>
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