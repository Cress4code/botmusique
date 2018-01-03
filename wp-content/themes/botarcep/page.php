<?php get_header(); ?>

<?php if (have_posts()): while (have_posts()) : the_post(); ?>
<section id="content" class="site_content">
    <div class="container">
        <div class="row">

            <main class="main_content col-md-12">
                <div class="blog_posts_wrapper blog_single blog_page_single">

                    <div id="post-44" class="blog_post_container post-44 page type-page status-publish hentry">

                        <header class="page-header page_main_title clearfix">
                            <h1 class="entry-title title post_title"><?php the_title(); ?></h1>					</header><!-- .page-header -->

                        <div class="asalah_hidden_schemas" style="display:none;">
    <span class="blog_meta_item blog_meta_date">
        <span class="screen-reader-text"></span>
    <time class="entry-date published updated" datetime="2015-05-17T06:26:11+00:00">May 17, 2015</time>
    </span><span class="blog_meta_item blog_meta_author"><span class="author vcard">
                                    <a class="meta_author_avatar_url" href="https://ahmad.works/writing/author/ahmad/">
                                        <img src="https://awcdn1.ahmad.works/writing/wp-content/uploads/2015/05/Author-150x150.jpg" width="25" height="25" alt="John Doe" class="avatar avatar-25 wp-user-avatar wp-user-avatar-25 alignnone photo"></a>
                                    <a class="url fn n" href="https://ahmad.works/writing/author/ahmad/">John Doe</a></span></span>
                        </div>
                        <div class="blog_post clearfix">






                            <div class="entry-content blog_post_text blog_post_description">
                                <?php the_content(); ?>
                            </div>

                            <div class="blog_post_control clearfix">

                            </div>


                        </div>
                    </div><!-- #post-## -->

                    <!-- Reading progress bar -->

                </div><!-- .blog_posts_wrapper -->
            </main><!-- .main_content -->


        </div> <!-- .row -->
    </div> <!-- .container -->
</section>
		<?php endwhile; ?>

		<?php else: ?>

			<!-- article -->
			<article>

				<h2><?php _e( 'Sorry, nothing to display.', 'html5blank' ); ?></h2>

			</article>
			<!-- /article -->

		<?php endif; ?>

		</section>
		<!-- /section -->
	</main>

<?php //get_sidebar(); ?>

<?php get_footer(); ?>
