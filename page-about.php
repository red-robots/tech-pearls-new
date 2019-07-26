<?php
/*
 *  Template Name: About
 */

get_header(); ?>

	<div id="primary" class="full-content-area clear page-about">
		<main id="main" class="site-main-subpage clear" role="main">
			<?php while ( have_posts() ) : the_post(); ?>
				<header class="title-wrap text-center">
					<div class="wrapper clear">
						<h1 class="page-title section-title2"><?php the_title(); ?></h1>
					</div>
				</header>

				<?php  
					$mission_statement = get_field('mission_statement');
					$mission_text_bg = get_field('mission_text_bg');
					$about_text = get_field('column2_content');
					$founder_name = get_field('our_founder');
					$founder_image = get_field('our_founder_image');
					$founder_bio = get_field('our_founder_bio');
					$mission_bg = '';
					if($mission_text_bg){
						$mission_bg = ' style="background-image:url('.$mission_text_bg['url'].')"';
					}
				?>
				
				<div class="content-columns clear">
					<div class="row clear">
						<div class="mission col"<?php echo $mission_bg?>>
							<div class="textwrap clear"><?php echo $mission_statement ?></div>
						</div>
						<div class="about col">
							<div class="textwrap clear"><?php echo $about_text ?></div>
						</div>
					</div>
				</div>

				<div class="our-founder clear">
					<div class="titlediv">
						<div class="wrapper clear"><h2 class="section-title2 text-center">Our Founder</h2></div>
					</div>

					<div class="med-wrapper clear">
						<?php if ($founder_image) { ?>
						<div class="imagecol">
							<img src="<?php echo $founder_image['url'] ?>" alt="<?php echo $founder_image['title'] ?>" />
						</div>
						<?php } ?>
						<div class="bio-text <?php echo ($founder_image) ? 'half':'full' ?>">
							<?php if ($founder_name) { ?>
								<h2 class="founder-name"><?php echo $founder_name ?></h2>
							<?php } ?>
							<?php echo $founder_bio ?>
						</div>
					</div>
				</div>
				

			<?php endwhile; ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();
