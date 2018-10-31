<?php $view->get("profile/head"); ?>
<section class="min-fullscreen" style="overflow: visible;">
    <div class="ph_text" style="margin-top: -200px">
        <div class="ph_profile_list">
            <?php
                foreach($_var->profile->subscriptions as $id){
                    $profile = new \App\Models\Account\User($id);
                    echo $profile->toHtml();
                }
            ?>
        </div>
    </div>
</section>