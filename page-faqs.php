<?php
/**
 * Template Name: FAQ's
 *
 */

get_header(); ?>

	<div id="primary" class="full-content-area clear page-faqs">

		<main id="main" class="full-site-main clear" role="main">
			<header class="title-wrap text-center">
				<div class="wrapper clear">
					<h1 class="page-title section-title2"><?php the_title(); ?></h1>
				</div>
			</header>

			<div class="main-content-wrapper clear">
				<?php  
					$img_bg = get_field('faq_image_background');
				?>
				<?php if ($img_bg) { ?>
				<div class="image-bg" style="background-image:url('<?php echo $img_bg['url'] ?>');">
					<div class="image-overlay"></div>
				</div>	
				<?php } ?>

				<div class="content-inner clear">
		
					<?php while ( have_posts() ) : the_post(); ?>
						<?php if ( get_the_content() ) { ?>
							<div class="entry-content"><?php the_content(); ?></div>
						<?php } ?>
					<?php endwhile; ?>

					<?php if(have_rows('faq')): ?>
					<section class="faqs med-wrapper clear">
						<?php while(have_rows('faq')): the_row();
							$question=get_sub_field('question');
							$answer=get_sub_field('answer');
							?>
								<div class="faqrow clear">
									<div class="question clear">
										<?php the_sub_field('question'); ?>
										<div class="plus-minus-toggle"><span class="arrow"></span></div>
									</div>
									<div class="answer clear"><?php the_sub_field('answer'); ?></div>
								</div><!-- faqrow -->
						<?php endwhile; ?>
					</section>
					<?php endif; ?>

				</div>
			</div>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();