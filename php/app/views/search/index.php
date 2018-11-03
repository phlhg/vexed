<section class="min-fullscreen">
    <div class="ph_text">
        <h1>Entdecken</h1>
        <?php if(count($_var->profile_suggestions)){ ?>
            <h3><?=$_var->profile_suggestions_title?></h3>
            <div class="ph_profile_list duo"><?php
                foreach($_var->profile_suggestions as $id){
                    $profile = new \App\Models\Account\User($id);
                    echo $profile->toHtml();
                }
            ?></div>
        <?php } ?>
        <h3>Neueste Beiträge</h3>
        <div class="ph_post_list">
            <div class="container"><?php
                foreach($_var->post_suggestions as $id){
                    $post = new \App\Models\Post\Post($id);
                    echo $post->toHtmlBanner(true);
                }
            ?></div>
        </div>
    </div>
</section>