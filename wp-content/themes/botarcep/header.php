<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <title><?php wp_title(''); ?><?php if (wp_title('', false)) {
            echo ' :';
        } ?><?php bloginfo('name'); ?></title>
    <?php
    $siteImage = get_all_image();

    ?>
    <link href="//www.google-analytics.com" rel="dns-prefetch">
    <link href="<?php echo $siteImage['favicon']; ?>" rel="shortcut icon">
    <link href="<?php echo $siteImage['favicon']; ?>" rel="apple-touch-icon-precomposed">


    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php bloginfo('description'); ?>">

    <?php wp_head(); ?>
    <script>
        // conditionizr.com
        // configure environment tests
        conditionizr.config({
            assets: '<?php echo get_template_directory_uri(); ?>',
            tests: {}
        });
    </script>

</head>

<body class="home blog">

<!-- Load facebook SDK -->
<div id="fb-root"></div>
<script>(function (d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s);
        js.id = id;
        js.async = true;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.7&appId=1028508873847234";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<!-- End Load facebook SDK -->


<div id="page" class="hfeed site">


    <!-- start site main container -->
    <div class="site_main_container">
        <!-- header -->

        <header class="site_header">
            <!-- top menu area -->
            <div class="top_menu_wrapper">
                <div class="container">

                    <div class="mobile_menu_button">
                        <span class="mobile_menu_text">Menu</span>
                        <span>-</span><span>-</span><span>-</span>
                    </div>

                    <div class="top_header_items_holder">

                        <div class="main_menu pull-left">
                            <div class="main_nav">
                                <?php myheader_nav() ?>
                            </div>
                        </div>

                        <div class="header_icons pull-right text_right">
                            <!-- start header social icons -->
                            <?php
                            $rsocial=get_all_rsocial_link();
                            ?>
                            <div class="social_icons_list header_social_icons pull-left">
                                <a rel="nofollow" target="_blank" href="<?php  echo $rsocial->facebook?>" title="Facebook"
                                   class="social_icon social_facebook social_icon_facebook"><i
                                            class="fa fa-facebook"></i></a>`
                                <a rel="nofollow" target="_blank" href="<?php  echo $rsocial->twitter?>" title="Twitter"
                                   class="social_icon social_twitter social_icon_twitter"><i class="fa fa-twitter"></i></a>
                            </div>                            <!-- end header social icons -->

                            <!-- start search box -->
                            <div class="header_search pull-right">
                                <form class="search clearfix animated searchHelperFade" method="get" id="searchform"
                                      action="https://ahmad.works/writing/">
                                    <input class="col-md-12 search_text" id="appendedInputButton"
                                           placeholder="Hit enter to search" type="text" name="s">
                                    <input type="hidden" name="post_type" value="post">
                                    <i class="fa fa-search"><input type="submit" class="search_submit" id="searchsubmit"
                                                                   value=""></i>
                                </form>
                            </div>
                            <!-- end search box -->
                        </div>
                    </div> <!-- end .top_header_items_holder -->

                </div>
            </div>
            <!-- top menu area -->
            <!-- header logo wrapper -->
            <div class="header_logo_wrapper  ">
                <div class="container">
                    <div class="logo_wrapper">
                        <style>.site_logo_image {
                                width: 170px;
                            }

                            .site_logo_image {
                                height: 70px;
                            }</style>
                        <a class="asalah_logo retina_logo" title="Writing" href="<?php echo home_url() ?>" rel="home">
                            <img width="170" height="70" src="<?php echo $siteImage['logo']; ?>"
                                 class="site_logo img-responsive site_logo_image pull-left clearfix"
                                 alt="<?php bloginfo('name') ?>">
                        </a>

                        <a class="asalah_logo default_logo  has_retina_logo" title="Writing"
                           href="<?php echo home_url() ?>" rel="home">
                            <img width="170" height="70" src="<?php echo $siteImage['logo']; ?>"
                                 class="site_logo img-responsive site_logo_image pull-left clearfix"
                                 alt="<?php bloginfo('name') ?>">
                        </a>
                        <h1 class="screen-reader-text site_logo site-title pull-left clearfix">Writing</h1>
                    </div>
                    <div class="header_info_wrapper">

                        <!-- <a id="user_info_icon" class="user_info_icon user_info_button skin_color_hover" href="#">
                            <i class="fa fa-align-center"></i>
                        </a> -->

                        <a id="user_info_icon"
                           class="user_info_avatar user_info_avatar_icon user_info_button skin_color_hover" href="#">
                            <i class="fa fa-align-center"></i>
                        </a>

                    </div>
                </div>

            </div>
            <!-- header logo wrapper -->
        </header>
        <!-- header -->



