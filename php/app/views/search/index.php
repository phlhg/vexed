<section class="min-fullscreen">
    <div class="ph_text">
        <form action="" method="POST">
            <input name="ph_signup_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
            <div class="ph_form_input_submit">
                <input name="q" autofocus="" value="" type="text" placeholder="Entdecke Nutzer, Beiträge, Hashtags, ..."/>
                <span class="ph_fi_submit"><i class="material-icons">search</i></span>
            </div>
            <span class="ph_form_error"></span>
            <span class="ph_form_info"></span>
            <input type="submit" value="Suchen"/>
        </form>
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