<?php /* Template Name: Accueil */
get_header(); ?>

<!-- start stie content -->
<section id="content" class="site_content">
    <div class="container">
        <div class="row">


            <h4 class="page-title screen-reader-text">Blog Posts</h4>
            <main class="main_content col-md-12">


                <div class="blog_posts_wrapper blog_posts_list clearfix  banners_blog_style narrow_content_width">

                    <?php
                    $defaults = array(
                        'numberposts' => 5,
                        'post_type' => 'Sonnerie',
                        'post_status' => 'publish',
                    );
                    $posts = get_posts($defaults);


                    if (!empty($posts)) {
                        foreach ($posts as $post) {
                            if (!empty(get_image_url($post->ID, "full"))) {
                                $imageUrl = get_image_url($post->ID, "full");
                            }
                            if (!empty($url = get_field("dennonciation_url_du_fichier", $post->ID))) {
                                $imageUrl = $url;
                            }

                            $terms = get_the_category()

                            ?>
                            <article id="post-74"
                                     class="blog_post_container post-74 post type-post status-publish format-image has-post-thumbnail hentry category-images-posts category-life-style tag-people post_format-post-format-image">


                                <div class="blog_post clearfix">
                                    <div class="posts_list_wrapper clearfix">
                                        <div class="post_thumbnail_wrapper">
                                            <div class="blog_post_banner blog_post_image">
                                                <a href="<?php echo get_the_permalink($post->ID) ?>"
                                                   title="<?php echo $post->post_title; ?>">
                                                    <img width="940" height="400"
                                                         src="<?php echo $imageUrl; ?>"
                                                         class="img-responsive wp-post-image"
                                                         alt="<?php echo $post->post_title; ?>">
                                                </a></div>
                                        </div>
                                        <div class="post_info_wrapper">
                                            <!-- use this wrapper in list style only to group all info far from thumbnail wrapper -->


                                            <div class="blog_post_title">
                                                <h2 class="entry-title title post_title">
                                                    <a href="<?php echo get_the_permalink($post->ID) ?>"
                                                       title="<?php echo get_the_title($post); ?>"><?php echo get_the_title($post->ID); ?></a>
                                                </h2>
                                            </div>

                                            <div class="blog_post_meta clearfix">
    <span class="blog_meta_item blog_meta_format entry_format">
                            <a href="https://ahmad.works/writing/type/image/"><i class="fa fa-camera-retro"></i></a>
                        </span><span class="blog_meta_item blog_meta_category">Classé

                                                    |
                                                    <?php
                                                    //the_taxonomies(";");
                                                    $categories = get_the_terms($post->ID, 'organisme-denonciation');
                                                    if (!empty($categories)) {
                                                        foreach ($categories as $key => $categorie) {
                                                            ?>

                                                            <a href="<?php echo get_term_link($categorie->term_id) ?>">

                                            <i class="fa fa-bookmark">  </i> <?php echo $categorie->name ?>
                                        </a>

                                                            <?php
                                                        }
                                                    }
                                                    ?>


                                                </span>

                                                <span class="blog_meta_item blog_meta_date">
                                                    <span class="screen-reader-text">

                                                    </span>
                                                    <time class="entry-date published updated"
                                                          datetime="<?php echo get_the_time('F j, Y'); ?>">
                                                  <?php echo get_the_time('F j, Y'); ?>
                                                      </time>
                                                </span>
                                                <span class="blog_meta_item blog_meta_comments">
                                                    <a href="<?php echo get_the_permalink($post->ID) ?>#comments">
                                                        <?php echo get_comments_number($post->ID) ?>
                                                        Comments</a>
                                                </span>

                                            </div>


                                            <div class="entry-content blog_post_text blog_post_description">
                                                <?php echo wp_trim_words($post->post_content, 100, "[..]"); ?>
                                            </div>

                                            <div class="blog_post_control clearfix">


                                                <div class="blog_post_control_item blog_post_readmore">
                                                    <a href="<?php echo get_the_permalink($post->ID) ?>"
                                                       title="<?php echo $post->post_title; ?>"
                                                       class="read_more_link">Lire la suite</a></div>

                                                <div class="blog_post_control_item blog_post_share">
                                                    <span class="share_item share_sign"><i
                                                                class="fa fa-share "></i></span>

                                                    <span class="social_share_item_wrapper">
                                                        <a rel="nofollow"
                                                           href="https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink($post->ID) ?>"
                                                           class="share_item share_item_social share_facebook"
                                                           onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php echo get_the_permalink($post->ID) ?>', 'facebook-share-dialog', 'width=626,height=436');
                                                                   return false;"><i
                                                                    class="fa fa-facebook"></i></a></span>

                                                    <span class="social_share_item_wrapper"><a rel="nofollow"
                                                                                               href="<?php echo get_the_permalink($post->ID) ?>"
                                                                                               target="_blank"
                                                                                               class="share_item share_item_social share_twitter"><i
                                                                    class="fa fa-twitter"></i></a></span>

                                                    <span class="social_share_item_wrapper"><a rel="nofollow"
                                                                                               href="<?php echo get_the_permalink($post->ID) ?>"
                                                                                               onclick="javascript:window.open(this.href,
                                        '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
                                return false;" class="share_item share_item_social share_googleplus"><i
                                                                    class="fa fa-google-plus"></i></a></span>

                                                    <span class="social_share_item_wrapper"><a rel="nofollow"
                                                                                               href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php echo get_the_permalink($post->ID) ?>"
                                                                                               target="_blank"
                                                                                               class="share_item share_item_social share_linkedin"><i
                                                                    class="fa fa-linkedin"></i></a></span>

                                                    <span class="social_share_item_wrapper">
                                                        <a rel="nofollow"
                                                           onClick="window.open('whatsapp://send?text=*<?php echo urlencode(get_the_title($post)) . '\n' ?>*  <?php echo wp_trim_words($post->post_content, 30, "[..]"); ?> <?php echo urlencode(get_the_permalink($post->ID)) ?>', 'Whatsapp', 'width=585,height=666,left=' + (screen.availWidth / 2 - 292) + ',top=' + (screen.availHeight / 2 - 333) + ''); return false;"
                                                           href="whatsapp://send?text=<?php echo urlencode(get_the_permalink($post->ID)) ?>"
                                                           class="share_item share_item_social share_pinterest"
                                                           target="_blank"><i class="fa fa-whatsapp"></i></a></span>


                                                </div>
                                            </div>

                                        </div> <!-- .post_info_wrapper close post_info_wrapper in cas of list style-->
                                    </div> <!-- .posts_list_wrapper -->

                                </div>
                            </article><!-- #post-## -->


                            <?php
                        }
                    }
                    ?>
                    <!-- Reading progress bar -->

                </div> <!-- .blog_posts_wrapper -->


                <nav class="navigation pagination" role="navigation">
                    <h2 class="screen-reader-text">Posts navigation</h2>
                    <div class="nav-links"><span class="page-numbers current">1</span>
                        <a class="page-numbers" href="#">2</a>
                        <a class="next page-numbers" href="#"><i
                                    class="fa fa-angle-right"></i></a></div>
                </nav>
            </main><!-- .main_content -->


        </div> <!-- .row -->
    </div> <!-- .container -->
</section> <!-- .site_content -->


<?php get_footer(); ?>
