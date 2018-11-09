<section class="transparent-menu">
    <div class="ph_profile_bg"><div style="background-image: url(/img/pbg/<?=$_var->profile->id?>);"></div></div>
    <div class="ph_profile_bg_overlay"></div>
    <div class="ph_profile_bg_easing"></div>
    <div class="ph_profileh_content">
        <div class="ph_pb_wrapper"><div style="background-image: url(/img/pb/<?=$_var->profile->id?>)" class="pb"></div></div>
        <div class="main">
            <span class="meta">
                <?=($_var->profile->admin ? '<span><strong>ADMIN</strong></span>' : '')?>
                <span><strong><?=count(\App\Models\Post\Post::byUser($_var->profile->id))?></strong>Beiträge</span>
                <a href="/p/<?=$_var->profile->name?>/followers/"><strong><?=count($_var->profile->followers)?></strong>Abonnenten</a>
                <a href="/p/<?=$_var->profile->name?>/subscriptions/" ><strong><?=count($_var->profile->subscriptions)?></strong>abonniert</a></span>
            <h1><?=$_var->profile->name?></h1>
        </div>
        <span class="description">
            <?=nl2br($_var->profile->description)?>
            <?php if($_var->profile->website != ""){ ?>
                <a class="ph_profile_website" href="http://<?=preg_replace('/https?:\/\//i','',$_var->profile->website)?>" target="_blank"><?=$_var->profile->website?></a>
            <?php } ?>
        </span>
        <div class="ph_profile_submenu">
            <span>
                <?php if($_var->profile->relation == \App\Models\Account\Relation::ME){ ?>
                    <a href="/p/<?=$_var->profile->name?>/edit/" class="ph_fs_button fake" data-rel="9">
                        <span class="edit"><span class="txt">Bearbeiten </span><i class="material-icons">create</i></span>
                    </a>
                <?php } else { ?>
                    <div class="ph_fs_button" data-rel="<?=$_var->profile->relation?>" data-id="<?=$_var->profile->id?>">
                        <span class="edit"><span class="txt">Bearbeiten </span><i class="material-icons">create</i></span>
                        <span class="follow"><span class="txt">Folgen </span><i class="material-icons">add</i></span>
                        <span class="requested"><span class="txt">Angefragt </span><i class="material-icons">send</i></span>
                        <span class="following"><span class="txt">Abonniert </span><i class="material-icons">done</i></span>
                    </div>
                <?php } ?>
            </span>
            <?php if($_var->p_site){ ?>
                <span>
                    <a href="/p/<?=$_var->profile->name?>/" target="_blank" class="ph_button_v2 i">
                        <span class="icon" style="right: unset; left: 10px"><i class="material-icons">chevron_left</i></span><span class="txt"><?=$_var->p_site_title?></span>
                    </a>
                </span>
                <?php if($_var->profile->relation == \App\Models\Account\Relation::ME){ ?>
                    <span>
                        <div class="ph_button_v2 multi double">
                            <a class="sect" href="/chat/"><i class="material-icons">chat_bubble_outline</i></a>
                            <a class="sect" href="/logout/"><i class="material-icons">power_settings_new</i></a>
                        </div>
                    </span>
                <?php } else { ?>
                    <span>
                        <div class="ph_button_v2 multi double">
                            <a class="sect" href="/chat/"><i class="material-icons">chat_bubble_outline</i></a>
                            <span class="sect" ><i class="material-icons">more_horiz</i></span>
                        </div>
                    </span>
                <?php } ?>
            <?php } else { ?>
                <?php if($_var->profile->relation == \App\Models\Account\Relation::ME){ ?>
                    <span>
                        <a href="/chat/" class="ph_button_v2">
                            <span class="txt">Nachrichten </span><i class="material-icons">chat_bubble_outline</i>
                        </a>
                    </span>
                    <span>
                        <a href="/logout/" class="ph_button_v2">
                            <span class="txt">Abmelden </span><i class="material-icons">power_settings_new</i>
                        </a>
                    </span>
                <?php } else { ?>
                    <span>
                        <a href="/chat/" target="_blank" class="ph_button_v2">
                            <span class="txt">Nachricht </span><i class="material-icons">chat_bubble_outline</i>
                        </a>
                    </span>
                    <span>
                        <a class="ph_button_v2 ">
                            <span class="txt">Mehr</span><i class="material-icons">more_horiz</i>
                        </a>
                    </span>
                <?php } ?>
            <?php } ?>
        </div>
    </div>
</section>