	</div><!-- #content -->
	<footer id="colophon" class="site-footer clear" role="contentinfo">
		<div class="wrapper">
			<?php 
				$company_email = get_field('company_email','option');
				$facebook = get_field('facebook','option');
				$menu_lists = array();
				for($i=1;$i<=3;$i++) {
					$menu_type = 'Footer Menu ' . $i;
					$args = wp_get_nav_menu_items($menu_type);
					if($args) {
						$menu_lists[] = $args;
					}
				}
			?>
			<?php if($company_email || $facebook) { ?>
			<div class="footer-menu-column company-info">
				<?php if($company_email) { ?>
				<div class="link">
					<a href="mailto:<?php echo $company_email;?>"><?php echo $company_email;?></a>
				</div>
				<?php } ?>
				<?php if($facebook) { ?>
				<div class="link social-media">
					<a href="<?php echo $facebook;?>" target="_blank"><i class="fab fa-facebook"></i></a>
				</div>
				<?php } ?>
			</div>
			<?php } ?>

			<?php if($menu_lists) { ?>
				<?php foreach($menu_lists as $menus) { ?>
				<div class="footer-menu-column">
					<ul class="footer-list">
					<?php foreach($menus as $m) {
						$menu_url = $m->url;
						$menu_title = $m->title; ?>
						<li><a href="<?php echo $menu_url;?>"><?php echo $menu_title;?></a></li>
					<?php } ?>
					</ul>
				</div>
				<?php } ?>
			<?php } ?>
		</div><!-- wrapper -->
		<div class="copyright-section clear"></div>
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
