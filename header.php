<?php
/**
 * The header for theme.
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package ACStarter
 */
//use Dompdf\Dompdf;
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i" rel="stylesheet">
<script defer src="<?php bloginfo( 'template_url' ); ?>/assets/svg-with-js/js/fontawesome-all.js"></script>

<?php  
global $post;
$cert_url = '';
$post_slug = ( isset($post->post_name) ) ? $post->post_name : '';
if($post_slug=="my-account") {
	$cert_post_id = get_active_certificate();
	$cert_url = ($cert_post_id) ? get_permalink($cert_post_id) : '';
}
?>
<script type="text/javascript">
	var siteURL = '<?php echo get_site_url()?>';
	var certURL = '<?php echo $cert_url?>';
</script>
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="site clear">
	<a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content', 'acstarter' ); ?></a>
	<header id="masthead" class="site-header clear" role="banner">
		<div class="wrapper clear">
			<div class="header-inner clear">
				<?php if( get_custom_logo() ) { ?>
		            <div class="logo">
		            	<?php the_custom_logo(); ?>
		            </div>
		        <?php } else { ?>
		            <h1 class="logo">
			            <a href="<?php bloginfo('url'); ?>"><?php bloginfo('name'); ?></a>
		            </h1>
		        <?php } ?>


				<nav id="site-navigation" class="main-navigation" role="navigation">
					<div class="outer-menu-container clear">
						<div class="main-menu-container">
							<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-menu', 'container'=>false ) ); ?>
							<div class="user-navi">
								<?php
									$cart_total =  WC()->cart->get_cart_contents_count();
									$cart_total_text = ($cart_total>0) ? ' <span class="cart-total">('.$cart_total.')</span>':'';
								?>
								<?php if(is_user_logged_in()) { ?>
									<a href="<?php echo get_site_url()?>/my-account/">My Account</a>
								<?php } else { ?>
									<a href="<?php echo get_site_url()?>/my-account/">Login</a>
								<?php } ?>
								<a class="cart-customlocation" href="<?php echo wc_get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>">My Cart<?php echo $cart_total_text;?></a>
							</div>
						</div>
					</div>
					<div class="secondary-menu-container clear">
						<?php wp_nav_menu( array( 'menu' => 'Secondary Menu', 'container'=>false ) ); ?>
					</div>
				</nav>
			</div>
		</div>

		<div id="mobile-navigation" class="mobile-navigation" role="navigation">
			<div class="mobile-inner clear">
				<div class="mobile-main-nav">
					<?php wp_nav_menu( array( 'theme_location' => 'primary', 'menu_id' => 'primary-mobile-menu','container_class'=>'mobile-content-nav' ) ); ?>
					<?php wp_nav_menu( array( 'menu' => 'Secondary Menu', 'container_class'=>'secondary-content-nav' ) ); ?>
				</div>
			</div>
		</div>
		<span id="toggleMenu" class="burger"><i></i></span>
	</header><!-- #masthead -->

	<?php if( is_home() || is_front_page() ) { 
		get_template_part('template-parts/banner');
	} ?>

	<div id="content" class="site-content clear">
