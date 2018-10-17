<section style="overflow: visible;" class="min-halfscreen transparent-menu">
    <div class="ph_profile_bg"><div style="background-image: url(/img/pbg/<?=$_var->profile->id?>);"></div></div>
    <div class="ph_profile_bg_overlay"></div>
    <div class="ph_profileh_content">
        <img src="/img/pb/<?=$_var->profile->id?>" class="pb"/>
        <div class="main">
            <span class="meta">
                <?=($_var->profile->admin ? '<span><strong>ADMIN</strong></span>' : '')?>
                <span><strong><?=$_var->profile->posts?></strong>Beiträge</span>
                <span><strong><?=$_var->profile->followers?></strong>Abonnenten</span>
                <span><strong><?=$_var->profile->following?></strong>abonniert</span></span>
            <h1><?=$_var->profile->name?></h1>
        </div>
        <span class="description"><?=$_var->profile->description?></span>
        <div class="ph_profile_submenu">
            <span>
                <div class="ph_fs_button">
                    <span class="follow">Folgen <i class="material-icons">add</i></span>
                    <span class="requested">Angefragt <i class="material-icons">send</i></span>
                    <span class="following">Abonniert <i class="material-icons">done</i></span>
                </div>
            </span>
            <?php if($_var->profile->website != ''){ ?>
                <span>
                    <a href="http://<?=$_var->profile->website?>" target="_blank" class="ph_button_v2 i">
                        <span class="icon"><i class="material-icons">public</i></span> <?=$_var->profile->website?>
                    </a>
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
<section class="min-fullscreen" style="overflow: visible;">
    <div class="ph_text">
        <div class="ph_post_list" style="margin-top: -200px"><!--
            <?php
                $files = [["16/DSC_0863.JPG","16/DSC_0872.JPG","16/DSC_0874.JPG","16/DSC_0880.JPG","16/DSC_0891.JPG","16/DSC_0893.JPG","16/DSC_0901.JPG","16/DSC_0906.JPG","16/DSC_0909.JPG","16/DSC_0912.JPG"],["15/DSC_0802.JPG","15/DSC_0805.JPG","15/DSC_0809.JPG","15/DSC_0811.JPG","15/DSC_0816.JPG","15/DSC_0821.JPG","15/DSC_0825.JPG","15/DSC_0834.JPG","15/DSC_0846.JPG","15/DSC_0855.JPG"],["14/DSC_0758.JPG","14/DSC_0764.JPG","14/DSC_0771.JPG","14/DSC_0773.JPG","14/DSC_0776.JPG","14/DSC_0781.JPG","14/DSC_0783.JPG"],["13/DSC_0569.JPG","13/DSC_0570.JPG","13/DSC_0577.JPG","13/DSC_0603.JPG","13/DSC_0606.JPG","13/DSC_0613.JPG","13/DSC_0666.JPG","13/DSC_0676.JPG","13/DSC_0692.JPG","13/DSC_0694.JPG","13/DSC_0702.JPG","13/DSC_0709.JPG"],["10/DSC_0269.JPG","10/DSC_0334.JPG","10/DSC_0337.JPG","10/DSC_0346.JPG","10/DSC_0355.JPG","10/DSC_0356.JPG","10/DSC_0369.JPG","10/DSC_0399.JPG","10/DSC_0401.JPG","10/DSC_0403.JPG"],["9/DSC_0251.JPG","9/DSC_0252.JPG"],["11/20171019_155733013_iOS.jpg","11/DSC_0230.JPG","11/DSC_0231.JPG","11/DSC_0233.JPG","11/DSC_0234.JPG","11/DSC_0241.JPG"],["12/20170327_135107274_iOS.jpg","12/20170327_141100531_iOS.jpg","12/DSC_0067.JPG","12/DSC_0082.JPG","12/DSC_0094.JPG","12/DSC_0212.JPG","12/DSC_0214.JPG"],["7/DSC_3020.JPG","7/DSC_3022.JPG","7/DSC_3025.JPG","7/DSC_3028.JPG","7/DSC_3029.JPG","7/DSC_3036.JPG","7/DSC_3037.JPG","7/DSC_3041.JPG","7/DSC_3069.JPG","7/DSC_3074.JPG","7/DSC_3093.JPG"],["8/DSC_2849.JPG","8/DSC_2854.JPG","8/DSC_2858.JPG","8/DSC_2864.JPG","8/DSC_2869.JPG","8/DSC_2870.JPG"],["6/DSC_2782.JPG","6/DSC_2785.JPG","6/DSC_2786.JPG","6/DSC_2789.JPG","6/DSC_2791.JPG","6/DSC_2793.JPG","6/DSC_2796.JPG","6/DSC_2799.JPG","6/DSC_2810.JPG","6/DSC_2811.JPG","6/DSC_2819.JPG","6/DSC_2831.JPG"],["5/DSC_2619.JPG","5/DSC_2630.JPG","5/DSC_2635.JPG","5/DSC_2637.JPG"],["4/sunset_zurich.jpg"]];
            ?>
            <?php foreach($files as $i => $album){ ?>
            <?php
                $year = 2018-$i;
            ?>
            <?php if($i != 0){ ?>--><h2 class="postt"><?=$year?></h2><!--<?php } ?>
            --><div class="container"><!--
                <?php foreach($album as $img){ ?>
                    --><a class="post" href="#">
                        <img class="main" src="http://phlhg.vs/album/pics/<?=$img?>"/>
                        <div class="meta">

                        </div>
                    </a><!--
                <?php } ?>
            --></div><!--
            <?php } ?>
        --></div>
    </div>
</section>