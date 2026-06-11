<?php
/**
 * WooCommerce Rosa layout wrapper.
 *
 * @package Di Restaurant
 */

get_header( 'rosa' );
?>

<main class="rosa-page rosa-page--shop" id="content">
	<div class="rosa-page-body rosa-page-body--shop">
		<div class="rosa-page-body__inner">
			<?php if ( is_shop() && ! is_search() ) : ?>
				<header class="rosa-shop-header rosa-animate">
					<p class="rosa-section__subtitle">Đặt món</p>
					<span class="rosa-section__divider" aria-hidden="true">&#10043;</span>
					<h1 class="rosa-page-hero__title"><?php woocommerce_page_title(); ?></h1>
					<p class="rosa-shop-header__lead">
						Chọn món yêu thích, thêm vào giỏ hàng và đặt mang về hoặc giao tận nơi.
					</p>
				</header>
			<?php endif; ?>

			<div class="rosa-woo-content rosa-animate rosa-animate--delay">
				<?php woocommerce_content(); ?>
			</div>
		</div>
	</div>
</main>

<?php
get_footer( 'rosa' );
