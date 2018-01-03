<?php if (have_posts()): while (have_posts()) : the_post(); ?>
    <article id="post-74" class="blog_post_container post-74 post type-post status-publish format-image has-post-thumbnail hentry category-images-posts category-life-style tag-people post_format-post-format-image">


        <div class="blog_post clearfix">
            <div class="posts_list_wrapper clearfix">
                <div class="post_thumbnail_wrapper">
                    <div class="blog_post_banner blog_post_image">
                        <a href="https://ahmad.works/writing/restaurant-employer-read-clients-orders-on-his-ipad/" title="Restaurant Employer Read Clients Orders On His iPad">
                            <img width="940" height="400" src="https://awcdn1.ahmad.works/writing/wp-content/uploads/2015/05/Resturant-Employer-940x400.jpg" class="img-responsive wp-post-image" alt="Resturant-Employer">
                        </a>        </div>						</div>
                <div class="post_info_wrapper"> <!-- use this wrapper in list style only to group all info far from thumbnail wrapper -->



                    <div class="blog_post_title">
                        <h2 class="entry-title title post_title">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                        </h2>
                    </div>

                    <div class="blog_post_meta clearfix">
                        <span class="blog_meta_item blog_meta_format entry_format">
                            <a href="https://ahmad.works/writing/type/image/"><i class="fa fa-camera-retro"></i></a>
                        </span><span class="blog_meta_item blog_meta_category">In <a href="https://ahmad.works/writing/category/images-posts/" rel="category tag">Images Posts</a>, <a href="https://ahmad.works/writing/category/life-style/" rel="category tag">Life Style</a></span><span class="blog_meta_item blog_meta_tags">Tags <a href="https://ahmad.works/writing/tag/people/" rel="tag">people</a></span><span class="blog_meta_item blog_meta_date"><span class="screen-reader-text"></span><time class="entry-date published updated" datetime="2015-05-17T23:42:46+00:00">May 17, 2015</time></span><span class="blog_meta_item blog_meta_comments"><a href="https://ahmad.works/writing/restaurant-employer-read-clients-orders-on-his-ipad/#comments">5 Comments</a></span><span class="blog_meta_item blog_meta_author"><span class="author vcard"><a class="meta_author_avatar_url" href="https://ahmad.works/writing/author/ahmad/"><img src="https://awcdn1.ahmad.works/writing/wp-content/uploads/2015/05/Author-150x150.jpg" width="25" height="25" alt="John Doe" class="avatar avatar-25 wp-user-avatar wp-user-avatar-25 alignnone photo"></a> <a class="url fn n" href="https://ahmad.works/writing/author/ahmad/">John Doe</a></span></span>				</div>


                    <div class="entry-content blog_post_text blog_post_description">
                        <?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>
                    </div>

                    <div class="blog_post_control clearfix">


                        <div class="blog_post_control_item blog_post_readmore">
                            <a href="https://ahmad.works/writing/restaurant-employer-read-clients-orders-on-his-ipad/" class="read_more_link">Continue Reading</a>                  </div>

                        <div class="blog_post_control_item blog_post_share">
                            <span class="share_item share_sign"><i class="fa fa-share "></i></span>

                            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.facebook.com/sharer/sharer.php?u=https://ahmad.works/writing/restaurant-employer-read-clients-orders-on-his-ipad/" class="share_item share_item_social share_facebook" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u=https://ahmad.works/writing/restaurant-employer-read-clients-orders-on-his-ipad/', 'facebook-share-dialog', 'width=626,height=436');
                                return false;"><i class="fa fa-facebook"></i></a></span>

                            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://twitter.com/share?url=https://ahmad.works/writing/restaurant-employer-read-clients-orders-on-his-ipad/" target="_blank" class="share_item share_item_social share_twitter"><i class="fa fa-twitter"></i></a></span>

                            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://plus.google.com/share?url=https://ahmad.works/writing/restaurant-employer-read-clients-orders-on-his-ipad/" onclick="javascript:window.open(this.href,
                                        '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');
                                return false;" class="share_item share_item_social share_googleplus"><i class="fa fa-google-plus"></i></a></span>

                            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.linkedin.com/shareArticle?mini=true&amp;url=https://ahmad.works/writing/restaurant-employer-read-clients-orders-on-his-ipad/" target="_blank" class="share_item share_item_social share_linkedin"><i class="fa fa-linkedin"></i></a></span>

                            <span class="social_share_item_wrapper"><a rel="nofollow" href="https://www.pinterest.com/pin/create/button/?url=https://ahmad.works/writing/restaurant-employer-read-clients-orders-on-his-ipad/&amp;media=https://ahmad.works/writing/wp-content/uploads/2015/05/Resturant-Employer.jpg&amp;description=Restaurant%20Employer%20Read%20Clients%20Orders%20On%20His%20iPad" class="share_item share_item_social share_pinterest" target="_blank"><i class="fa fa-pinterest"></i></a></span>










                        </div>
                    </div>

                </div> <!-- .post_info_wrapper close post_info_wrapper in cas of list style-->
            </div> <!-- .posts_list_wrapper -->

        </div>
    </article><!-- #post-## -->

	<!-- article -->
	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<!-- post thumbnail -->
		<?php if ( has_post_thumbnail()) : // Check if thumbnail exists ?>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
				<?php the_post_thumbnail(array(120,120)); // Declare pixel size you need inside the array ?>
			</a>
		<?php endif; ?>
		<!-- /post thumbnail -->

		<!-- post title -->
		<h2>
			<a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
		</h2>
		<!-- /post title -->

		<!-- post details -->
		<span class="date"><?php the_time('F j, Y'); ?> <?php the_time('g:i a'); ?></span>
		<span class="author"><?php _e( 'Published by', 'html5blank' ); ?> <?php the_author_posts_link(); ?></span>
		<span class="comments"><?php if (comments_open( get_the_ID() ) ) comments_popup_link( __( 'Leave your thoughts', 'html5blank' ), __( '1 Comment', 'html5blank' ), __( '% Comments', 'html5blank' )); ?></span>
		<!-- /post details -->

		<?php html5wp_excerpt('html5wp_index'); // Build your custom callback length in functions.php ?>

		<?php edit_post_link(); ?>

	</article>
	<!-- /article -->

<?php endwhile; ?>

<?php else: ?>

	<!-- article -->
	<article>
		<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>
	</article>
	<!-- /article -->

<?php endif; ?>
