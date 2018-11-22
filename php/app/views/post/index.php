<style> .ph_post { margin-bottom: 20px; } </style>
<section class="min-fullscreen">
    <div class="ph_text ph_text_feed" style="padding-bottom: 0; padding-left: 0; padding-right: 0; max-width: 720px; "><?php
        echo $_var->post->toHtmlFeed();
    ?></div>
    <div class="ph_text" style="padding-top: 0; padding-bottom: 0; max-width: 720px; ">
        <form action="" method="POST">
            <input name="ph_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
            <div class="ph_form_input_submit">
                <input required="" autocomplete="off" type="text" name="comment" placeholder="Kommentieren"/> 
                <span class="ph_fi_submit"><i class="material-icons" style="line-height: 31px; font-size: 20px;">chat_bubble_outline</i></span>
            </div>
            <span class="ph_form_error"><?=$_var->comment_error?></span>
            <span class="ph_form_info"><?=$_var->comment_info?></span>
            <input type="submit" value="Anmelden"/>
        </form>
    </div>
    <?php if(count($_var->post->comments) > 0){ ?>
        <div class="ph_text ph_text_feed" style="padding-top: 0; padding-bottom: 0; padding-left: 0; padding-right: 0; max-width: 720px; "><?php
            foreach($_var->post->comments as $id){
                $comment = new \App\Models\Comment\Comment($id);
                echo $comment->toHtml();
            }
        ?></div>
    <?php } ?>
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