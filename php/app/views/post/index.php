<section class="min-fullscreen">
    <div class="ph_text ph_text_feed" style="padding-bottom: 0; padding-left: 0; padding-right: 0; max-width: 720px; "><?php
        echo $_var->post->toHtmlFeed();
    ?>
    </div>
    <div class="ph_text" style="padding-top: 0; max-width: 760px; ">
        <?php if(count($_var->post->upvotes) > 0){ ?>
            <h3>Upvotes</h3>
            <div class="ph_profile_list">
                <?php
                    foreach($_var->post->upvotes as $id){
                        $profile = new \App\Models\Account\User($id);
                        echo $profile->toHtml();
                    }
                ?>
            </div>
        <?php } ?>
    </div>
</section>