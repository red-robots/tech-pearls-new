<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package ACStarter
 */
$post_type = get_post_type();
get_header(); ?>

	<div id="primary" class="full-content-area area">
		<main id="main" class="site-main wrapper" role="main">
		<?php
		while ( have_posts() ) : the_post();
			if($post_type=='product') { ?>
			<div class="entry-content"><?php the_content(); ?></div>	
			<?php } else {
				get_template_part( 'template-parts/content', get_post_format() );
			}
		endwhile; // End of the loop.
		?>
		<?php echo do_shortcode('[pearls_quiz_lesson_button]'); ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
