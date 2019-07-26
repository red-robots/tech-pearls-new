<?php
/**
 * The template for displaying archive pages.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package ACStarter
 */

get_header(); 
$obj = get_queried_object();
$page_slug = ( isset($obj->rewrite) && $obj->rewrite ) ? $obj->rewrite['slug'] : '';
if($page_slug=='courses') { ?>

	<?php get_template_part('template-parts/content','courses'); ?>
	
<?php } else { ?>

<div id="primary" class="full-content-area clear default-theme">
	<main id="main" class="site-main-subpage" role="main">

	<?php
	if ( have_posts() ) : ?>

		<header class="page-header">
			<?php
				the_archive_title( '<h1 class="page-title">', '</h1>' );
				the_archive_description( '<div class="taxonomy-description">', '</div>' );
			?>
		</header><!-- .page-header -->

		<?php
		/* Start the Loop */
		while ( have_posts() ) : the_post();

			/*
			 * Include the Post-Format-specific template for the content.
			 * If you want to override this in a child theme, then include a file
			 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
			 */
			get_template_part( 'template-parts/content', get_post_format() );

		endwhile;

		the_posts_navigation();

	else :

		get_template_part( 'template-parts/content', 'none' );

	endif; ?>

	</main><!-- #main -->
</div>

<?php } ?>

<?php
get_footer();
