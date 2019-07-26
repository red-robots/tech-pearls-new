<?php
/*
 * Template Name: My Account
*/

$is_woo_content = false;

$obj = get_queried_object();
$full_url = ( isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on' ? 'https' : 'http' ) . '://' .  $_SERVER['HTTP_HOST'] . $_SERVER["REQUEST_URI"];
$pagelink = get_permalink();
$url = str_replace($pagelink,'',$full_url);

$url_parts = explode("/",$url);
$url_parts = ($url_parts && array_filter($url_parts)) ? array_filter($url_parts) : false;


if( is_user_logged_in() ) {
	if($url_parts) {
		if( in_array('view-order',$url_parts) ) {
			$is_woo_content = true;
		}
	}
	if( wc_get_account_menu_items() ) {
		foreach ( wc_get_account_menu_items() as $endpoint => $label ) {
			if($url_parts) {
				if( in_array($endpoint, $url_parts)) {
					$is_woo_content = true;
					break;
				}
			}
			
		}
	}
}

get_header(); ?>

	<div id="primary" class="full-content-area clear default-theme">
		<main id="main" class="site-main-subpage" role="main">
			<?php while ( have_posts() ) : the_post(); ?>

				<header class="title-wrap text-center">
					<div class="wrapper clear">
						<h1 class="page-title"><?php the_title(); ?></h1>
					</div>
				</header>
				<div class="wrapper page-content-wrap content-left-col">
					<?php if( is_user_logged_in() ) { ?>
					
						<div class="sidebar col-left">
							<?php do_action( 'woocommerce_account_navigation' ); ?>
						</div>

						<div class="col-right entry-content">
							<?php if ($is_woo_content) { ?>
							<div class="woocommerce-MyAccount-content">
								<?php do_action( 'woocommerce_account_content' ); ?>
							</div>
							<?php } else { ?>
								<?php 
									$content = get_the_content();
									$content = strip_shortcodes( $content );
									$the_content = apply_filters("the_content",$content);
									echo $the_content;
									// get_template_part('inc/myprofile');
									// ^^^ shortcode output updated, 
									// your /inc/myprofile page needs an update if you want to use it
									echo do_shortcode('[pearls_my_account]');
									//the_content();
								?>
							<?php } ?>
						</div>

					<?php } else {  ?>

						<div class="login-wrapper clear">
			
							<div class="formcol left">
								<form class="woocommerce-form woocommerce-form-login login" method="post">
									<div class="formtitle"><span>Log In</span></div>
									<?php do_action( 'woocommerce_login_form_start' ); ?>

									<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
										<label for="username"><?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
										<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
									</p>
									<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
										<label for="password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
										<input class="woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" id="password" autocomplete="current-password" />
									</p>

									<?php do_action( 'woocommerce_login_form' ); ?>

									<p class="form-row">
										<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
											<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
										</label>
										<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
										<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"><?php esc_html_e( 'Log in', 'woocommerce' ); ?></button>
									</p>
									<p class="woocommerce-LostPassword lost_password">
										<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>"><?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?></a>
									</p>

									<?php do_action( 'woocommerce_login_form_end' ); ?>
								</form>
							</div>

							<?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) : ?>
							<div class="formcol right">
								<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
									<div class="formtitle"><span>Register</span></div>
									<?php do_action( 'woocommerce_register_form_start' ); ?>

									<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

										<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
											<label for="reg_username"><?php esc_html_e( 'Username', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
											<input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
										</p>

									<?php endif; ?>

									<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
										<label for="reg_email"><?php esc_html_e( 'Email address', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
										<input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
									</p>

									<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

										<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
											<label for="reg_password"><?php esc_html_e( 'Password', 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
											<input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
										</p>

									<?php else : ?>

										<p><?php esc_html_e( 'A password will be sent to your email address.', 'woocommerce' ); ?></p>

									<?php endif; ?>

									<?php do_action( 'woocommerce_register_form' ); ?>

									<p class="woocommerce-FormRow form-row">
										<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
										<button type="submit" class="woocommerce-Button button" name="register" value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
									</p>

									<?php do_action( 'woocommerce_register_form_end' ); ?>

								</form>
							</div>
							<?php endif; ?>

						</div>

					<?php } ?>

				</div>
			<?php endwhile; ?>
		</main><!-- #main -->
	</div><!-- #primary -->

<?php
get_footer();

