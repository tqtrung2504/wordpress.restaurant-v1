<?php
/**
 * Rosa menu page content.
 *
 * @package Di Restaurant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once get_template_directory() . '/inc/core/rosa-vietnamese-data.php';

$img_base = get_template_directory_uri() . '/assets/images/rosa/';
$hero_img = get_theme_mod( 'rosa_menu_image', $img_base . 'menu.jpg' );
$menu_sections = di_restaurant_rosa_get_vietnamese_menu();
?>

<section class="rosa-page-hero" style="background-image: url('<?php echo esc_url( $hero_img ); ?>');">
	<div class="rosa-page-hero__overlay"></div>
	<div class="rosa-page-hero__content">
		<p class="rosa-section__subtitle">Khám phá</p>
		<span class="rosa-section__divider" aria-hidden="true">&#10043;</span>
		<h1 class="rosa-page-hero__title">Thực đơn</h1>
		<p class="rosa-page-hero__lead">
			Ẩm thực Việt truyền thống, nguyên liệu tươi ngon, chế biến tinh tế từng món.
		</p>
	</div>
</section>

<section class="rosa-page-body">
	<div class="rosa-page-body__inner">
		<?php foreach ( $menu_sections as $section ) : ?>
			<div class="rosa-menu-section">
				<h2 class="rosa-menu-section__title"><?php echo esc_html( $section['title'] ); ?></h2>
				<ul class="rosa-menu-list">
					<?php foreach ( $section['items'] as $item ) : ?>
						<li class="rosa-menu-item rosa-animate">
							<?php if ( ! empty( $item['image'] ) ) : ?>
								<div class="rosa-menu-item__image">
									<img src="<?php echo esc_url( $item['image'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>" loading="lazy" width="140" height="140">
								</div>
							<?php endif; ?>
							<div class="rosa-menu-item__content">
								<div class="rosa-menu-item__header">
									<h3 class="rosa-menu-item__name"><?php echo esc_html( $item['name'] ); ?></h3>
									<span class="rosa-menu-item__price"><?php echo esc_html( di_restaurant_rosa_format_vnd( $item['price'] ) ); ?></span>
								</div>
								<p class="rosa-menu-item__description"><?php echo esc_html( $item['description'] ); ?></p>
							</div>
						</li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endforeach; ?>

		<p class="rosa-page-body__note">
			Thực đơn có thể thay đổi theo mùa và tình trạng nguyên liệu. Vui lòng báo trước nếu bạn dị ứng thực phẩm.
		</p>

		<p class="rosa-page-body__action">
			<a class="rosa-btn" href="<?php echo esc_url( home_url( '/reservations/' ) ); ?>">
				Đặt bàn
			</a>
		</p>
	</div>
</section>
