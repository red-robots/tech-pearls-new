<?php
/**
 * The template for Homepage
 *
 */

get_header(); ?>

<div id="primary" class="full-content-area clear">
	<?php while ( have_posts() ) : the_post(); ?>
		<?php $second_section_title = get_field('second_section_title'); ?>
		<?php if($second_section_title) { ?>
			<div class="fullwidth-text-section"><p><?php echo $second_section_title?></p></div>
		<?php } ?>

		<?php
			$advatages = array();
			for($i=1; $i<=3; $i++) {
				$icon_field = 'icon_'.$i;
				$title_field = 'icon_'.$i.'_title';
				$description_field = 'icon_'.$i.'_description';
				$advatages[] = array(
						'icon'	=> get_field($icon_field),
						'title'	=> 	get_field($title_field),
						'description' => get_field($description_field)
					);
			}
			if($advatages) { ?>
				<section class="section section-advatages clear">
					<div class="wrapper">
						<div class="row clear">
						<?php foreach($advatages as $a) { 
							$icon = $a['icon'];
							$title = $a['title'];
							$description = $a['description'];
							if($title && $description) { ?>
								<div class="column">
									<div class="pad clear">
										<?php if($icon) { ?>
											<div class="icon"><img src="<?php echo $icon['url'];?>" alt="<?php echo $icon['title'];?>" /></div>
										<?php } ?>
										<div class="descriptiondiv clear">
										<?php if($title) { ?>
											<p class="title"><?php echo $title; ?></p>
										<?php } ?>
										<?php if($description) { ?>
											<p class="description"><?php echo $description; ?></p>
										<?php } ?>
										</div>
									</div>
								</div>
							<?php } ?>
						<?php } ?>
						</div>


						<?php
							$button_text2 = get_field('second_section_button_text');
							$button_link2 = get_field('second_section_button_link');
						?>
						<?php if($button_text2 && $button_link2) { ?>
						<div class="cta-div clear text-center">
							<a href="<?php echo $button_link2;?>"><?php echo $button_text2;?></a>
						</div>
						<?php } ?>
					</div>
				</section>
			<?php } ?>	

			<?php
				$course_section_title = get_field('course_section_title');
				$section3_BgImage = get_field('courses_background_image');
				$section3Style = '';
				if($section3_BgImage) {
					$section3Style = ' style="background-image:url('.$section3_BgImage['url'].')"';
				}
				$course_lists = get_field('courses_types');
				$course_button_title = get_field('third_section_button_title');
				$course_button_link = get_field('third_section_button_link');
			?>

			<?php if($course_lists) { ?>
				<section class="section section-courses clear">
					<?php if($course_section_title) { ?>
					<div class="fullwidth-text-section">
						<p><?php echo $course_section_title?></p>
					</div>
					<?php } ?>
					<div class="outer-wrapper clear"<?php echo $section3Style;?>>
						<div class="overlay"></div>
						<div class="wrapper">
							<div class="row clear">
								<?php foreach ($course_lists as $cc) { 
									$icon = $cc['icon'];
									$title = $cc['section_title'];
									$description = $cc['description']; 
									$list_title = $cc['list_title'];
									$lists = $cc['lists'];
									$see_more_link = $cc['see_more_link'];
									if($title && $description) { ?>
										<div class="column">
											<div class="pad clear">
												<?php if($icon) { ?>
													<div class="icon"><img src="<?php echo $icon['url'];?>" alt="<?php echo $icon['title'];?>" /></div>
												<?php } ?>
												<div class="descriptiondiv clear">
												<?php if($title) { ?>
													<p class="title"><?php echo $title; ?></p>
												<?php } ?>
												<?php if($description) { ?>
													<p class="description"><?php echo $description; ?></p>
												<?php } ?>
												</div>

												<?php if($list_title) { ?>
												<div class="list-column">
													<div class="list-title"><span class="cta"><?php echo $list_title; ?></span></div>
													<?php if($lists) { ?>
														<ul class="links">
															<?php foreach($lists as $s) { 
																$obj_id = $s->ID;
																$list_link = get_permalink($obj_id);
																$list_name = $s->post_title;
															?>
															<li><a href="<?php echo $list_link?>"><?php echo $list_name?></a></li>
															<?php } ?>
														</ul>
														<?php if($see_more_link) { ?>
														<div class="more-link">
															<a href="<?php echo $see_more_link?>"><span>see more<i></i></span></a>
														</div>
														<?php } ?>
													<?php } ?>
												</div>
												<?php } ?>
											</div>
										</div>	
									<?php } ?>
								<?php } ?>
							</div>
							<?php if($course_button_title && $course_button_link) { ?>
							<div class="course-cta-button text-center clear">
								<a class="cta-btn yellow" href="<?php echo $course_button_link; ?>"><?php echo $course_button_title; ?></a>
							</div>
							<?php } ?>
						</div>
					</div>
				</section>
			<?php } ?>


		<?php
		/* TESTIMONIAL SECTION & SUBSCRIBE FORM */
		$args = array(
			'posts_per_page'    => 8,
		    'orderby'           => 'menu_order',
		    'order'             => 'ASC',
		    'post_type'         => 'testimonial',
		    'post_status'       => 'publish'  
	    );
	    $count_posts = count( get_posts($args) );
		$items = new WP_Query($args); ?>
			
		<section class="section section-testimonial clear">
			<div class="wrapper">
				<?php if ( $items->have_posts() ) { ?>
					<div class="testimonial column">
						<div class="inside clear">
							<div class="carousel-list">
								<ul id="featuredTestimonial" class="list">
									<?php while ( $items->have_posts() ) : $items->the_post();  ?>
									<?php if( get_the_content() ) { ?>
									<li class="testimonial-text">
										<div class="text"><?php the_content(); ?></div>
										<div class="author"><span class="author">- <?php the_title(); ?></span></div>
									</li>
									<?php } ?>
									<?php endwhile; wp_reset_postdata(); ?>
								</ul>
							</div>
							<?php if($count_posts>1) { ?>
								<div class="pager-nav"></div>
							<?php } ?>
						</div>
					</div>
				<?php } ?>

				<?php
					$section_4_title = get_field('section_4_title');
					$section_4_sub_title = get_field('section_4_sub_title');
					$subscribe_form_shortcode = get_field('subscribe_form_shortcode');
				?>
				<?php if( $subscribe_form_shortcode && do_shortcode($subscribe_form_shortcode) ) { ?>
					<div class="subcribe-form column">
						<div class="inside clear">
							<div class="form-wrapper clear">
								<div class="titlediv clear">
									<?php if($section_4_title) { ?>
										<h2 class="formtitle1"><?php echo $section_4_title;?></h2>
									<?php } ?>	
									<?php if($section_4_sub_title) { ?>
										<div class="formtitle2"><?php echo $section_4_sub_title;?></div>
									<?php } ?>	
								</div>
								<?php echo do_shortcode($subscribe_form_shortcode); ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
		</section>
			

	<?php endwhile;  ?>
</div><!-- #primary -->

<?php
get_footer();
