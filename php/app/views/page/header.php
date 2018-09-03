<!DOCTYPE html>
<html>
    <!--HEAD-->
    <head>
        <title><?php echo $view->getTitle(); ?></title>
        <!--// META //-->
        <meta charset="UTF-8"/>
        <meta http-equiv="language" content="deutsch, de">
        <meta name="revisit" content="After 10 days" />
        <meta name="robots" content="INDEX,FOLLOW" />
        <meta name="viewport" content="width=device-width, height=device-height, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="theme-color" content="<?php echo $view->meta->themecolor; ?>" />
        <!--// SEO //-->
        <meta name="description"  content="<?php echo $view->meta->description; ?>" />
        <meta name="keywords" content="<?php echo implode(",",$view->meta->keywords); ?>" />
        <meta name="page-topic" content="social network" />
        <meta name="language" content="Deutsch" />
        <!-- OPENGRAPH -->
        <meta property="og:title" content="<?php echo $view->getTitle(); ?>" />
        <meta property="og:description" content="<?php echo $view->meta->description; ?>" />
        <meta property="og:image" content="<?php echo $view->meta->image; ?>" />
        <meta property="og:url" content="<?php echo (strpos($_SERVER["SERVER_PROTOCOL"],"https") > -1 ? "https://" : "http://").strtolower($_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]); ?>" />
        <meta property="og:type" content="website" />
        <!--// WEBAPP //-->
        <meta name="apple-mobile-web-app-capable" content="yes" />
        <meta name="apple-mobile-web-app-title" content="<?php echo \Core\Config::get("application_title"); ?>">
        <meta name="application-name" content="<?php echo \Core\Config::get("application_title"); ?>">
        <meta name="apple-mobile-web-app-status-bar-style" content="default" />
        <!--// LINKS //-->
        <!-- FONTS -->
        <link href="https://fonts.googleapis.com/css?family=Oxygen:300,400,700" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
        <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">
        <!-- STYLESHEETS -->
        <link href="/css/main.css" rel="stylesheet" />
        <link href="/css/dark.css" rel="stylesheet" />
        <?php foreach($view->styles as $url){ ?>
            <link href="<?=$url?>" rel="stylesheet" />
        <?php } ?>
        <!-- SCRIPTS -->
        <script src="/js/main.js"></script>
    </head>
    <body>