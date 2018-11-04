<section class="min-fullscreen">
    <div class="ph_text">
        <h2 class="ph_mobile_hide">Neuer Beitrag</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input name="ph_tkn" type="hidden" value="<?php echo \Helpers\Token::get(); ?>" />
            <span class="ph_form_info"><?=$_var->form_info?></span>
            <div class="ph_form_input_submit">
                <textarea required="" max="250" name="description" placeholder="Deine Nachricht"><?=$_var->form_description?></textarea>
                <span class="ph_fi_submit"><i class="material-icons">chevron_right</i></span>
            </div>
            <!--<input type="file" name="image" accept="image/jpg, image/png" />-->
            <span class="ph_form_error"><?=$_var->form_error?></span>
            <input type="submit" value="Posten"/>
        </form>
    </div>
</section>