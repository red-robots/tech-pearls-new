<?php
get_header(); ?>

	<div id="primary" class="full-content-area clear default-theme">
		<main id="main" class="site-main-subpage" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<header class="title-wrap text-center">
					<div class="wrapper clear">
						<h1 class="page-title"><?php the_title(); ?></h1>
					</div>
				</header>
				<div class="wrapper page-content-wrap">
					<div class="entry-content">
						<?php the_content(); ?>
					</div>
				</div>
			<?php endwhile; ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
