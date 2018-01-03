<?php ///* Template Name: Demo Page Template */ get_header();

$sun=Sonnerieperso::attachFile("https://cdn.fbsbx.com/v/t59.3654-21/26541058_1569666399779356_7217441684773142528_n.mp3/157_Tiwa-Savage-ft.-Dr-SID-If-I-Start-To-Talk-SHOW2BABI.COM-00_00_00-00_00_29.mp3?oh=b5f93b6c42735dfec719127f4d63f5f7&oe=5A4DB73B", $post_id);
var_dump_pre($sun);
?>

	<main role="main">
		<!-- section -->
		<section>

			<h1><?php the_title(); ?></h1>

		<?php if (have_posts()): while (have_posts()) : the_post(); ?>

			<!-- article -->
			<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

				<?php the_content(); ?>

				<?php comments_template( '', true ); // Remove if you don't want comments ?>

				<br class="clear">

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

		</section>
		<!-- /section -->
	</main>

<?php get_sidebar(); ?>

<?php get_footer(); ?>
