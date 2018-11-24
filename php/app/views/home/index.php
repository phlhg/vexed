<section class="min-fullscreen">
    <div class="ph_text ph_text_feed" style="padding-left: 0; padding-right: 0; padding-bottom: 0; max-width: 720px; ">
        <?php if($_var->notice != ""){ ?>
            <div class="ph_notice"><?=$_var->notice?></div>
        <?php } ?>
    </div>
    <div id="feed" class="ph_text ph_text_feed" style="padding-left: 0; padding-right: 0; padding-top: 0; max-width: 720px; ">
        <?=$_var->content?>
    </div>
</section>