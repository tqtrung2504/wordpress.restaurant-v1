<?php
/**
 * Rosa reservations page content.
 *
 * @package Di Restaurant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img_base = get_template_directory_uri() . '/assets/images/rosa/';
$hero_img = get_theme_mod( 'rosa_delight_image', $img_base . 'delight.jpg' );
?>

<section class="rosa-page-hero" style="background-image: url('<?php echo esc_url( $hero_img ); ?>');">
	<div class="rosa-page-hero__overlay"></div>
	<div class="rosa-page-hero__content">
		<p class="rosa-section__subtitle">Ẩm thực</p>
		<span class="rosa-section__divider" aria-hidden="true">&#10043;</span>
		<h1 class="rosa-page-hero__title">Đặt bàn</h1>
		<p class="rosa-page-hero__lead">
			Đặt bàn trước để có trải nghiệm ẩm thực Hà Nội trọn vẹn tại Hanoi Home Taste.
		</p>
	</div>
</section>

<section class="rosa-page-body">
	<div class="rosa-page-body__inner rosa-page-body__inner--narrow">
		<form class="rosa-form" id="rosa-reservation-form" method="post" novalidate>
			<?php wp_nonce_field( 'rosa_reservation_submit', 'rosa_reservation_nonce' ); ?>
			<input type="hidden" name="action" value="rosa_reservation_submit">

			<div class="rosa-form__row rosa-form__row--2">
				<div class="rosa-form__field">
					<label for="reservation-name">Họ tên <span>*</span></label>
					<input type="text" id="reservation-name" name="name" required>
				</div>
				<div class="rosa-form__field">
					<label for="reservation-email">Email <span>*</span></label>
					<input type="email" id="reservation-email" name="email" required>
				</div>
			</div>

			<div class="rosa-form__row rosa-form__row--2">
				<div class="rosa-form__field">
					<label for="reservation-phone">Số điện thoại <span>*</span></label>
					<input type="tel" id="reservation-phone" name="phone" required>
				</div>
				<div class="rosa-form__field">
					<label for="reservation-guests">Số khách <span>*</span></label>
					<select id="reservation-guests" name="guests" required>
						<?php for ( $i = 1; $i <= 10; $i++ ) : ?>
							<option value="<?php echo esc_attr( $i ); ?>"><?php echo esc_html( $i ); ?> người</option>
						<?php endfor; ?>
					</select>
				</div>
			</div>

			<div class="rosa-form__row rosa-form__row--2">
				<div class="rosa-form__field">
					<label for="reservation-date">Ngày <span>*</span></label>
					<input type="date" id="reservation-date" name="date" required>
				</div>
				<div class="rosa-form__field">
					<label for="reservation-time">Giờ <span>*</span></label>
					<input type="time" id="reservation-time" name="time" required>
				</div>
			</div>

			<div class="rosa-form__field">
				<label for="reservation-notes">Ghi chú</label>
				<textarea id="reservation-notes" name="notes" rows="4" placeholder="Dị ứng thực phẩm, sinh nhật, vị trí bàn..."></textarea>
			</div>

			<div class="rosa-form__message" id="rosa-reservation-message" role="status" aria-live="polite"></div>

			<p class="rosa-form__action">
				<button type="submit" class="rosa-btn rosa-btn--solid">
					Xác nhận đặt bàn
				</button>
			</p>
		</form>

		<div class="rosa-info-cards">
			<div class="rosa-info-card">
				<h3>Giờ mở cửa</h3>
				<p>Thứ 2 – Thứ 6: 10:00 – 22:00<br>Thứ 7 – CN: 09:00 – 23:00</p>
			</div>
			<div class="rosa-info-card">
				<h3>Đoàn đông</h3>
				<p>Nhóm từ 10 người trở lên, vui lòng gọi trực tiếp để được hỗ trợ.</p>
			</div>
		</div>
	</div>
</section>
