<section class="min-fullscreen">
    <div class="ph_text">
        <?=$view->get("search/form")?>
        <?php if($_var->notice != ""){ ?>
            <div class="ph_notice"><?=$_var->notice?></div>
        <?php } ?>
        <?php if(count($_var->profile_suggestions)){ ?>
            <h3><?=$_var->profile_suggestions_title?></h3>
            <div class="ph_profile_list duo"><?php
                foreach($_var->profile_suggestions as $id){
                    $profile = new \App\Models\Account\User($id);
                    echo $profile->toHtml();
                }
            ?></div>
        <?php } ?>
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