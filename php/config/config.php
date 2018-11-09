<?php
    /**
     * # Configuration
     * Add properties by copying the line beneath and adjusting the 'value' and 'name'.
     * 
     *      $_CONFIG["name"] = "value"; 
     * 
     */
    $_CONFIG = [];

    //APPLICATION
    $_CONFIG["application_domain"] = "vxd.phlhg.ch";
    $_CONFIG["application_title"] = "VEXED";
    $_CONFIG["application_version"] = "1.0.0";
    $_CONFIG["application_version_stage"] = false; //For future error reporting

    //CONDITIONS
    $_CONFIG["conditions_version_last"] = 1536078668; 

    //DEFAULT
    $_CONFIG["default_meta_title_appendix"] = " | VEXED";
    $_CONFIG["default_meta_keywords"] = array("social network","social","share");
    $_CONFIG["default_meta_menu"] = true;
    $_CONFIG["default_meta_image"] = $_CONFIG["application_domain"]."/img/icons/dark_favicon.png";
    $_CONFIG["default_meta_themecolor"] = "rgb(250,200,0)";

    //E-MAIL
    $_CONFIG["email_noreply"] = "noreply@phlhg.ch";
    $_CONFIG["email_noreply_name"] = "VEXED";

    //HASH
    $_CONFIG["hash_salt"] = "ph_73238813";

    //USER / CLIENT
    $_CONFIG["user_guest_allow"] = false;

    //POST
    $_CONFIG["post_disabled"] = false;
    $_CONFIG["post_disabled_msg"] = "";

    //BLACKLISTS
    $_CONFIG["blacklist_websites"] = ["pornhub.com","xvideos.com","xnxx.com"];