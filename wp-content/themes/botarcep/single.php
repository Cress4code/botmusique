<?php get_header(); ?>
<?php if (have_posts()): while (have_posts()) : the_post();



    if (!empty(get_image_url(get_the_ID(), "full"))) {
        $imageUrl = get_image_url(get_the_ID(), "full");
    }
    if (!empty($url = get_field("dennonciation_url_du_fichier", get_the_ID()))) {
        $imageUrl = $url;
    }
?>

<section id="content" class="site_content">
    <div class="container">
        <div class="row">
            <main class="main_content col-md-12">
                <div class="blog_posts_wrapper blog_single blog_posts_single narrow_content_width">

                    <div id="post-74" class="blog_post_container post-74 post type-post status-publish format-image has-post-thumbnail hentry category-images-posts category-life-style tag-people post_format-post-format-image">


                        <div class="blog_post clearfix">


                            <div class="blog_post_banner blog_post_image"><img width="960" height="636" src="<?php echo $imageUrl ?>" class="img-responsive wp-post-image" alt="<?php the_title(); ?>" sizes="(max-width: 960px) 100vw, 960px">        </div>
                            <div class="blog_post_title">
                                <h1 class="entry-title title post_title"><?php the_title(); ?></h1>					</div>

                            <div class="blog_post_meta clearfix">

                                <span class="blog_meta_item blog_meta_format entry_format">
                                    <a href="#">
                                        <i class="fa fa-camera-retro"></i>
                                    </a>
                                </span>
                                <span class="blog_meta_item blog_meta_category">
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
                                    <span class="screen-reader-text"></span>
                                    <time class="entry-date published updated" datetime="<?php the_time('j F  Y'); ?> <?php the_time('g:i a'); ?>"><?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?></time>
                                </span>
                                <span class="blog_meta_item blog_meta_author">
                                    <span class="author vcard">
                                        <a class="meta_author_avatar_url" href="#">
                                            <img src="https://awcdn1.ahmad.works/writing/wp-content/uploads/2015/05/Author-150x150.jpg" width="25" height="25" alt="John Doe" class="avatar avatar-25 wp-user-avatar wp-user-avatar-25 alignnone photo">
                                        </a>
                                        <?php _e( 'Published by', 'html5blank' ); ?> <?php the_author_posts_link(); ?>
                                    </span>
                                </span>
                            </div>


                            <div class="entry-content blog_post_text blog_post_description">
                                <?php the_content(); // Dynamic Content ?>
                            </div>

                            <div class="blog_post_control clearfix">

                                <div class="blog_post_control_item blog_post_share">
                                    <span class="share_item share_sign"><i class="fa fa-share "></i></span>

                                    <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>" class="share_item share_item_social share_facebook" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>', 'facebook-share-dialog', 'width=626,height=436');
                                return false;"><i class="fa fa-facebook"></i></a></span>

                                    <span class="social_share_item_wrapper"><a rel="nofollow" href="https://twitter.com/share?url=<?php the_permalink(); ?>" target="_blank" class="share_item share_item_social share_twitter"><i class="fa fa-twitter"></i></a></span>

                                    <span class="social_share_item_wrapper"><a rel="nofollow" href="https://plus.google.com/share?url=<?php the_permalink(); ?>" onclick="javascript:window.open(this.href,
                                        '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
                                return false;" class="share_item share_item_social share_googleplus"><i class="fa fa-google-plus"></i></a></span>

                                    <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=<?php the_permalink(); ?>" target="_blank" class="share_item share_item_social share_linkedin"><i class="fa fa-linkedin"></i></a></span>

                                    <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.pinterest.com/pin/create/button/?url=<?php the_permalink(); ?>&amp;media=https://ahmad.works/writing/wp-content/uploads/2015/05/<?php the_title(); ?>.jpg&amp;description=Restaurant%20Employer%20Read%20Clients%20Orders%20On%20His%20iPad" class="share_item share_item_social share_pinterest" target="_blank"><i class="fa fa-pinterest"></i></a></span>










                                </div>
                            </div>


                        </div>
                    </div><!-- #post-## -->

                    
                </div><!-- .blog_posts_wrapper -->
            </main><!-- .main_content -->



        </div> <!-- .row -->
    </div> <!-- .container -->
</section>






	<?php endwhile; ?>

	<?php else: ?>

		<!-- article -->
		<article>

			<h1><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h1>

		</article>
		<!-- /article -->

	<?php endif; ?>




<?php get_footer(); ?>
