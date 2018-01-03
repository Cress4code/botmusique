<footer class="site-footer">
    <h3 class="screen-reader-text">Site Footer</h3>
    <div class="footer_wrapper">
        <div class="container">


            <div class="second_footer has_first_footer row">
                <div class="col-md-12">
                    <div class="text-center">
                        &copy; &nbsp;&nbsp; <?php echo "  ".date('Y')  ?><?php bloginfo('name')?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer><!-- .site-footer -->
</div><!-- .site_main_container -->

<!-- start site side container -->
<div class="sliding_close_helper_overlay"></div>
<div class="site_side_container ">
    <h3 class="screen-reader-text">Sliding Sidebar</h3>
    <div class="info_sidebar">
        <div id="search-2" class="widget_container widget_content widget widget_search clearfix">
            <form role="search" class="search-form" method="get" action="https://ahmad.works/writing/">
                <label>
                    <span class="screen-reader-text">Search for:</span>
                    <input type="search" class="search-field" placeholder="Search ..." value="" name="s"
                           title="Search for:">
                </label>
                <i class="search_submit_icon fa fa-search"><input type="submit" class="search-submit" value=""></i>
            </form>
        </div>
        <div id="about-widget-4" class="widget_container widget_content widget about-widget clearfix">
            <?php
            $about= get_field("site_about_page","option");
           $post=get_post($post);

            ?>
            <h4 class="widget_title title">
                <span class="page_header_title"><?php echo $post->post_title;?></span></h4>
            <div class="asalah_about_me">
                <div class="author_image_wrapper default rounded">
                    <img class="img-responsive"
                         src="<?php  echo get_image_url($post->ID,"full")?>" alt="About Me">
                </div>
                <div class="author_text_wrapper">
                    <?php
                        _e($post->post_content);
                    ?>
                </div>
            </div>
        </div>
        <div id="social-widget-3" class="widget_container widget_content widget asalah-social-widget clearfix">
            <h4 class="widget_title title"><span class="page_header_title">Social Profiles</span></h4>
            <div class="social_icons_list widget_social_icons_list">
                <a rel="nofollow" target="_blank" href="#" title="Facebook"
                   class="social_icon widget_social_icon social_facebook social_icon_facebook"><i
                            class="fa fa-facebook"></i></a>
                <a rel="nofollow" target="_blank" href="#" title="Twitter"
                   class="social_icon widget_social_icon social_twitter social_icon_twitter"><i
                            class="fa fa-twitter"></i></a>
            </div>
            <div id="postlist-widget-3" class="widget_container widget_content widget asalah-postlist-widget clearfix">
                <h4 class="widget_title title"><span class="page_header_title">Dernières Dénonciations</span></h4>
                <div class="asalah_post_list_widget">
                    <ul class="post_list">
                        <?php
                        $defaults = array(
                            'numberposts' => 5,
                            'post_type' => 'Sonnerie',
                            'post_status' => 'publish',
                        );
                        $posts = get_posts($defaults);


                        if (!empty($posts)) {
                            foreach ($posts as $post) {

                                ?>

                                <li class="post_item clearfix">
                                    <div class="post_thumbnail_wrapper">
                                        <a href="<?php echo get_the_permalink($post->ID) ?>"
                                           title="<?php echo $post->post_title; ?>"><img width="50" height="50"
                                                                                         src="<?php echo get_image_url($post->ID, "thumbnail") ?>"
                                                                                         class="img-responsive wp-post-image"
                                                                                         alt="Resturant-Employer"></a>
                                    </div>
                                    <div class="post_info_wrapper">
                                        <h5 class="title post_title"><a
                                                    href="<?php echo get_the_permalink($post->ID) ?>"
                                                    title="<?php echo $post->post_title; ?>"><?php echo $post->post_title; ?></a>
                                        </h5><span
                                                class="post_meta_item post_meta_time post_time"><?php echo get_the_time('F j, Y'); ?></span>
                                    </div>
                                </li>

                                <?php
                            }
                        }
                        ?>

                    </ul>
                </div>
            </div>
            <div id="fbpage-widget-3" class="widget_container widget_content widget asalah-fbpage-widget clearfix">
                <h4 class="widget_title title"><span class="page_header_title">Facebook</span></h4>
                <div class="fb-page" data-href="<?php echo ""?>" data-hide-cover="false"
                     data-show-facepile="true" data-show-posts="true" data-adapt-container-width="true">
                    <div class="fb-xfbml-parse-ignore"></div>
                </div>
            </div>
        </div>
    </div>
</div> <!-- end site side container .site_side_container -->
<!-- .site -->

<?php wp_footer(); ?>

<!-- analytics -->
<script>
    (function (f, i, r, e, s, h, l) {
        i['GoogleAnalyticsObject'] = s;
        f[s] = f[s] || function () {
            (f[s].q = f[s].q || []).push(arguments)
        }, f[s].l = 1 * new Date();
        h = i.createElement(r),
            l = i.getElementsByTagName(r)[0];
        h.async = 1;
        h.src = e;
        l.parentNode.insertBefore(h, l)
    })(window, document, 'script', '//www.google-analytics.com/analytics.js', 'ga');
    ga('create', 'UA-XXXXXXXX-XX', 'yourdomain.com');
    ga('send', 'pageview');
</script>

</body>
</html>
