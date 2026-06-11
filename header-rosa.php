<!DOCTYPE html>
<html <?php language_attributes(); ?> lang="vi">
<head>
	<?php do_action( 'di_restaurant_the_head' ); ?>
	<?php wp_head(); ?>
</head>
<body <?php body_class( 'rosa-body' ); ?> itemscope itemtype="http://schema.org/WebPage">

<?php
if ( function_exists( 'wp_body_open' ) ) {
	wp_body_open();
}
?>

<a class="skip-link screen-reader-text" href="#content">Chuyển tới nội dung</a>

<header class="rosa-header" id="rosa-header">
	<div class="rosa-header__inner">
		<div class="rosa-header__brand">
			<?php
			if ( has_custom_logo() ) {
				the_custom_logo();
			} else {
				?>
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="rosa-header__logo" rel="home">
					<?php echo esc_html( get_bloginfo( 'name' ) ); ?>
				</a>
				<?php
			}
			?>
		</div>

		<button class="rosa-header__toggle" type="button" aria-expanded="false" aria-controls="rosa-header-actions" aria-label="Mở menu">
			<span></span>
			<span></span>
			<span></span>
		</button>

		<div class="rosa-header__actions" id="rosa-header-actions">
			<nav class="rosa-header__nav" id="rosa-nav" aria-label="Menu chính">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'primary',
						'container'      => false,
						'menu_class'     => 'rosa-nav',
						'fallback_cb'    => 'Di_Restaurant_Rosa::nav_fallback',
						'depth'          => 1,
					)
				);
				?>
			</nav>
		</div>
	</div>
</header>
