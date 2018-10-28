<section style="overflow: visible;" class="min-halfscreen transparent-menu">
    <div class="ph_profile_bg"><div style="background-image: url(/img/pbg/<?=$_var->profile->id?>);"></div></div>
    <div class="ph_profile_bg_overlay"></div>
    <div class="ph_profile_bg_easing"></div>
    <div class="ph_profileh_content">
        <img src="/img/pb/<?=$_var->profile->id?>" class="pb"/>
        <div class="main">
            <span class="meta">
                <?=($_var->profile->admin ? '<span><strong>ADMIN</strong></span>' : '')?>
                <span><strong><?=$_var->profile->posts?></strong>Beiträge</span>
                <a href="/p/<?=$_var->profile->name?>/followers/"><strong><?=count($_var->profile->followers)?></strong>Abonnenten</a>
                <a href="/p/<?=$_var->profile->name?>/subscriptions/" ><strong><?=count($_var->profile->subscriptions)?></strong>abonniert</span></a>
            <h1><?=$_var->profile->name?></h1>
        </div>
        <span class="description"><?=$_var->profile->description?>
        </span>
        <div class="ph_profile_submenu">
            <span>
                <?php if($_var->profile->relation == \App\Models\Account\Relation::ME){ ?>
                    <div class="ph_fs_button SELF">
                <?php } else if($_var->profile->relation == \App\Models\Account\Relation::STRANGER){ ?>
                    <div class="ph_fs_button STRANGER">
                <?php } else if($_var->profile->relation == \App\Models\Account\Relation::REQUESTED){ ?>
                    <div class="ph_fs_button REQUESTED">
                <?php } else if($_var->profile->relation == \App\Models\Account\Relation::FOLLOWING){ ?>
                    <div class="ph_fs_button FOLLOWING">
                <?php } else { ?>   
                    <div class="ph_fs_button">
                <?php } ?>
                    <span class="edit">Bearbeiten <i class="material-icons">create</i></span>
                    <span class="follow">Folgen <i class="material-icons">add</i></span>
                    <span class="requested">Angefragt <i class="material-icons">send</i></span>
                    <span class="following">Abonniert <i class="material-icons">done</i></span>
                </div>
            </span>
            <?php if($_var->profile->website != ''){ ?>
                <span>
                    <?php if(!$_var->p_site){ ?>
                        <a href="http://<?=$_var->profile->website?>" target="_blank" class="ph_button_v2 i">
                            <span class="icon"><i class="material-icons">public</i></span> <?=$_var->profile->website?>
                        </a>
                    <?php } else { ?>
                    <a href="/p/<?=$_var->profile->name?>/" target="_blank" class="ph_button_v2 i">
                        <span class="icon" style="right: unset; left: 10px"><i class="material-icons">chevron_left</i></span><?=$_var->p_site_title?>
                    </a>
                <?php } ?>
                </span>
                <span>
                    <div class="ph_button_v2 multi triple">
                        <a class="sect" href="/chat/"><i class="material-icons">chat_bubble_outline</i></a>
                        <span class="sect" ><i class="material-icons">block</i></span>
                        <span class="sect" ><i class="material-icons">outlined_flag</i></span>
                    </div>
                </span>
            <?php } else { ?>
                <span>
                    <a href="/chat/" target="_blank" class="ph_button_v2 i">
                        Nachricht <i class="material-icons">chat_bubble_outline</i>
                    </a>
                </span>
                <span>
                    <div class="ph_button_v2 multi double">
                        <span class="sect" ><i class="material-icons">block</i></span>
                        <span class="sect" ><i class="material-icons">outlined_flag</i></span>
                    </div>
                </span>
            <?php } ?>
        </div>
    </div>
</section>