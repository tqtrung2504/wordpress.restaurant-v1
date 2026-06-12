<?php
/**
 * Rosa Cart page template.
 *
 * @package Di Restaurant
 */

get_header( 'rosa' );

require_once get_template_directory() . '/inc/core/rosa-vietnamese-data.php';

$img_base = get_template_directory_uri() . '/assets/images/rosa/';
$hero_img = get_theme_mod( 'rosa_menu_image', $img_base . 'menu.jpg' );
$brand    = di_restaurant_rosa_get_brand_name();
?>

<section class="rosa-page-hero rosa-page-hero--compact" style="background-image: url('<?php echo esc_url( $hero_img ); ?>');">
	<div class="rosa-page-hero__overlay"></div>
	<div class="rosa-page-hero__content">
		<p class="rosa-section__subtitle">Đặt món</p>
		<span class="rosa-section__divider" aria-hidden="true">&#10043;</span>
		<h1 class="rosa-page-hero__title">Giỏ hàng</h1>
		<p class="rosa-page-hero__lead">
			Kiểm tra món đã chọn tại <?php echo esc_html( $brand ); ?> trước khi thanh toán.
		</p>
	</div>
</section>

<main class="rosa-page rosa-page--cart" id="content">
	<div class="rosa-page-body rosa-page-body--shop">
		<div class="rosa-page-body__inner">
			<div class="rosa-woo-content rosa-woo-content--cart rosa-animate">
				<?php
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
				?>
			</div>
		</div>
	</div>
</main>

<?php
get_footer( 'rosa' );
