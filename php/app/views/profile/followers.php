<?php $view->get("profile/head"); ?>
<section class="min-halfscreen ph_afterphead">
    <div class="ph_text">
        <div class="ph_profile_list">
            <?php
                foreach($_var->profile->followers as $id){
                    $profile = new \App\Models\Account\User($id);
                    echo $profile->toHtml();
                }
            ?>
        </div>
    </div>
</section>