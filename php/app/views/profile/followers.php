<?php $view->get("profile/head"); ?>
<section class="min-fullscreen" style="overflow: visible;">
    <div class="ph_text">
        <?php
            foreach($_var->profile->followers as $id){
                $follower = new \App\Models\Account\User($id);
                echo '<a href="/p/'.$follower->name.'/">'.$follower->displayName."</a><br/>";
            }
        ?>
    </div>
</section>