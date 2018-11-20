<section class="min-fullscreen">
    <div class="ph_text ph_text_feed" style="padding-left: 0; padding-right: 0; max-width: 720px; ">
        <?php if($_var->notice != ""){ ?>
            <div class="ph_notice"><?=$_var->notice?></div>
        <?php } ?>
        <?=$_var->content?>
    </div>
</section>