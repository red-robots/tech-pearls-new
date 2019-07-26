<?php  
	// $obj = get_queried_object();
	// $page_title = ( isset($obj->label) && $obj->label ) ? $obj->label : '';
	$taxonomy = 'product_cat';
	$exclude = ['uncategorized'];
	$categories = get_terms( array(
	    'taxonomy' => $taxonomy,
	    'hide_empty' => false,
	    'parent' => 0
	) );
	if($categories) {
		foreach($categories as $k=>$cat) {
			if(in_array($cat->slug,$exclude)) {
				unset($categories[$k]);
			}
		}
	}
	$page_id = 23;
	$page_title = get_the_title($page_id);
	$courses = get_field('courses',$page_id);

	/* Get all categories */
	$sortedCats = array();
	$all_Categories = get_terms( array(
	    'taxonomy' => $taxonomy,
	    'hide_empty' => false
	) );

	// if($all_Categories){
	// 	foreach($all_Categories as  $ac) {
	// 		//$ss = $ac->slug;
	// 		$ss = $ac->term_id;
	// 		$sortedCats[$ss] = $ac;
	// 	}
	// }

?>
<div id="primary" class="full-content-area clear default-theme woocommerce">
	<main id="main" class="site-main-subpage" role="main">
		<header class="title-wrap text-center">
			<div class="wrapper clear">
				<h1 class="page-title"><?php echo $page_title ?></h1>
			</div>
		</header>

		<div class="courses-section clear">
			<div class="wrapper">
				<?php if($courses) { ?>
				<div class="flexrow">
					<?php foreach ($courses as $row) {
						$term = $row['title'];
						$term_id = ($term) ? $term->term_id : 0;
						$title = ($term) ? $term->name : '';
						$pagelink = ($term) ? get_term_link($term) : '';
						$description = $row['description'];
						$icon = $row['icon'];
						?>
						<div class="flexcol course">
							<?php if ($icon) { ?>
							<div class="icon">
								<img src="<?php echo $icon['url'] ?>" alt="<?php echo $icon['title'] ?>" aria-hidden="true" />
							</div>	
							<?php } ?>
							<h3 class="title"><?php echo $title; ?></h3>
							<div class="description"><?php echo $description ?></div>
							<?php if ($pagelink) { ?>
								<div class="button"><a href="#termId_<?php echo $term_id;?>">Start Now</a></div>	
							<?php } ?>
						</div>
					<?php } ?>
				</div>	
				<?php } ?>
			</div>
		</div>

		<?php if ($categories) { ?>
		<section class="section-course-options clear">
			<div class="inner-wrapper clear">
				<?php foreach ($categories as $cat) { 
					$cat_id = $cat->term_id;
					$cat_name = $cat->name;
					$post_type = 'product';
					$args = array(
						'posts_per_page'   => -1,
						'post_type'        => $post_type,
						'post_status'      => 'publish',
						'tax_query' => array(
				            array(
				                'taxonomy' => $taxonomy,
				                'field' => 'term_id',
				                'terms' => $cat_id
				            )
				        )
					);
					$items = get_posts($args);
					?>
					<h3 id="termId_<?php echo $cat_id?>" class="catname"><?php echo $cat_name ?></h3>
					<div class="course-listing clear">
						<?php if ($items) { ?>
						<div class="itemswrap clear">

							<?php  
							$sortedItemsChild = array();
							$childCatItems = array();
							$parentCatItems = array();
							foreach ($items as $item) { 
								$x_post_title = $item->post_title;
								$x_post_id = $item->ID;
								$x_post_cats = get_the_terms($x_post_id,$taxonomy);
								//$children_cats = get_term_children($cat_id,$taxonomy);
								$x_children_categories = array();
								if($x_post_cats){
									foreach ($x_post_cats as $xc) {
										$xtermId = $xc->term_id;
										$xc_catname = $xc->name;
										$xparentId = $xc->parent;
										$xc->post_title = $x_post_title;
										$xc->post_id = $x_post_id;
										//$xxItems[$x_post_id][] = $xc;
										if($xparentId>0) {
											$parentCatItems = array();
											$x_children_categories[$xtermId] = $xc->name;
											$xc->child_cats = $x_children_categories;
											$childCatItems[$xtermId][] = $xc;
										} else {
											$xc->child_cats = '';
											$parentCatItems[] = $xc;
										}
									}	
								}
							} 

							foreach( $all_Categories as $aa ) {
								$a_term_id = $aa->term_id;
								if( $childCatItems && array_key_exists($a_term_id, $childCatItems) ) {
									$i_items = $childCatItems[$a_term_id];
									foreach($i_items as $ii) {
										$postId = $ii->post_id;
										$sortedItemsChild[] = $ii;
									}
								}
							}
							?>

							<?php $finalItems = ($sortedItemsChild) ? $sortedItemsChild : $parentCatItems;  ?>

							<?php if ( $finalItems ) { $pageId = array();  ?>
								<?php $i=1; foreach ($finalItems as $sc) { 
									$boxClass = ($i % 2) ? 'odd':'even';
									$post_title = $sc->post_title;
									$post_id = $sc->post_id;
									$include = true;
									$childCatsCount = ($sc->child_cats) ? count($sc->child_cats) : 0;
									
									$pageId[] = array();
									$no_duplicate = true;
									if( in_array($post_id, $pageId) ) {
										$no_duplicate = false;
									} else {
										$pageId[] = $post_id;
									}

									$children_categories = ( isset($sc->child_cats) && $sc->child_cats ) ? implode(", ",$sc->child_cats):'';

									$i_product = wc_get_product( $post_id );
									$price = ($i_product) ? $i_product->get_regular_price() : '0.00';
									$price = wc_price($price);
									?>
									<?php if ($no_duplicate) { ?>
										<div id="item_<?php echo $post_id;?>" class="course-box clear <?php echo $boxClass ?>">
											<?php if ($children_categories) { ?>
											<div class="course-cat"><?php echo $children_categories ?></div>	
											<?php } ?>
											<div class="course-info clear">
												<div class="left">
													<div class="ctitle"><?php echo $post_title ?></div>
													<div class="clink"><a href="<?php echo get_permalink($post_id); ?>">View Details</a></div>
												</div>
												<div class="right">
													<div class="cprice"><?php echo $price ?></div>
													<div class="c_addcart_btn">
														<a href="/ac/pearls/product-category/continuing-education/?add-to-cart=412" data-quantity="1" class="button product_type_course add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo $post_id ?>" data-product_sku="" rel="nofollow">Add to cart</a>
													</div>
												</div>
											</div>
										</div>
									<?php $i++; } ?>

								<?php } ?>
							<?php } ?>
							
					
						</div>
						<?php } ?>
					</div>
				<?php } ?>
			</div>
		</section>
		<?php } ?>


	</main>
</div>