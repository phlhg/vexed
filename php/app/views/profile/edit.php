<form action="" method="POST" enctype="multipart/form-data">
    <section class="transparent-menu">
        <div class="ph_profile_bg"><div style="background-image: url(/img/pbg/<?=$_var->profile->id?>);"></div></div>
        <div class="ph_profile_bg_overlay"></div>
        <div class="ph_profile_bg_easing"></div>
        <div class="ph_profileh_content">
            <div id="pb_sel" class="ph_pb_wrapper"><div style="background-image: url(/img/pb/<?=$_var->profile->id?>)" class="pb"></div><div class="overlay"><i class="material-icons">add_a_photo</i></div></div>
            <div class="main">
                <span class="meta">
                    <?=($_var->profile->admin ? '<span><strong>ADMIN</strong></span>' : '')?>
                    <span><strong><?=$_var->profile->posts?></strong>Beiträge</span>
                    <a href="/p/<?=$_var->profile->name?>/followers/"><strong><?=count($_var->profile->followers)?></strong>Abonnenten</a>
                    <a href="/p/<?=$_var->profile->name?>/subscriptions/" ><strong><?=count($_var->profile->subscriptions)?></strong>abonniert</a></span>
                <h1><input name="username" placeholder="Nutzername" value="<?=$_var->profile->name?>"/></h1>
            </div>
            <span class="description">
                <textarea name="description" maxlength="255" placeholder="Deine Beschreibung"><?=$_var->profile->description?></textarea>
                <input class="ph_profile_website" placeholder="Deine Website" name="website" value="<?=$_var->profile->website?>"/>
                <?php if(count($_var->form_errors) > 0){ ?><br/>
                    <?php foreach($_var->form_errors as $err){ ?>
                        <span class="ph_form_error"><?=$err?></span>
                    <?php } ?>
                <?php } ?>
            </span>
            <div class="ph_profile_submenu">
                <span>
                    <div class="ph_fs_button fake" data-rel="9" data-id="<?=$_var->profile->id?>">
                        <span class="edit"><span class="txt">Fertig </span><i class="material-icons">check</i></span>
                    </div>
                </span>
                <span>
                    <a href="/p/<?=$_var->profile->name?>/" target="_blank" class="ph_button_v2">
                        <span class="txt">Abbrechen </span><i class="material-icons">close</i>
                    </a>
                </span>
                <span>
                    <div class="ph_button_v2 i" id="pbg_sel">
                        <span class="txt">Hintergrund </span><i class="material-icons">add_photo_alternate</i>
                    </div>
                </span>
            </div>
        </div>
        <input type="submit" value="Fertig" />
        <input style="display: none;" name="pb" id="p_pb" type="file" accept=".jpg, .png"/>
        <input style="display: none;" name="pbg" id="p_pbg" type="file" accept=".jpg, .png"/>
    </section>
</form>
<section class="min-halfscreen ph_afterphead">
    <div class="ph_text">
    </div>
</section>