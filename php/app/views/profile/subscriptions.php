<?php $view->get("profile/head"); ?>
<section class="min-fullscreen" style="overflow: visible;">
    <div class="ph_text">
        <?php
            foreach($_var->profile->subscriptions as $id){
                $subscribed = new \App\Models\Account\User($id);
                echo '<a href="/p/'.$subscribed->name.'/">'.$subscribed->displayName."</a><br/>";
            }
        ?>
    </div>
</section>