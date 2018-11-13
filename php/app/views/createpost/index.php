<section class="min-fullscreen">
    <div class="ph_text ph_text_feed" style="padding-left: 0; padding-right: 0; max-width: 800px;" >
        <form class="ph_post input" method="POST" action="" enctype="multipart/form-data">
            <input name="ph_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
            <div class="ph_post_media empty"><!--
                --><div class="actions">
                    <span id="post_img_open"><i class="material-icons">add_photo_alternate</i></span>
                    <span id="post_img_delete"><i class="material-icons">close</i></span>
                </div><!--
                --><img id="post_image_prev" src="/img/pb/<?=$view->client->id?>/" /><!--
            --></div>
            <div class="ph_post_info">
                <div class="profile">
                    <a href="/p/<?=$view->client->name?>/" class="pb" style="background-image: url(/img/pb/<?=$view->client->id?>/);"></a>
                </div>
                <textarea class="description desc_text" required="" max="250" name="description" placeholder="Deine Nachricht"><?=$_var->form_description?></textarea>
                <span class="meta">
                    Posten als <a class="p_link" href="/p/<?=$view->client->name?>/"><strong><?=$view->client->displayName?></strong></a>
                    <span style="float: right;" id="char_counter">0/255</span>
                </span>
                <span class="ph_form_info"><?=$_var->form_info?></span>
                <span class="ph_form_error"><?=$_var->form_error?></span>
                <span class="ph_fi_submit"><i class="material-icons">chevron_right</i></span>
            </div>
            <input style="display: none;" type="file" id="post_image" name="image" accept=".jpg, .png, .gif" />
            <input type="submit" value="Posten"/>
        </form>
    </div>
</section>