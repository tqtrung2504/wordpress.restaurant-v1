<?php
/**
 * Rosa contact page content.
 *
 * @package Di Restaurant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$img_base = get_template_directory_uri() . '/assets/images/rosa/';
$hero_img = get_theme_mod( 'rosa_story_image', $img_base . 'story.jpg' );
?>

<section class="rosa-page-hero" style="background-image: url('<?php echo esc_url( $hero_img ); ?>');">
	<div class="rosa-page-hero__overlay"></div>
	<div class="rosa-page-hero__content">
		<p class="rosa-section__subtitle">Liên hệ</p>
		<span class="rosa-section__divider" aria-hidden="true">&#10043;</span>
		<h1 class="rosa-page-hero__title">Liên hệ</h1>
		<p class="rosa-page-hero__lead">
			Chúng tôi luôn sẵn sàng lắng nghe. Liên hệ để đặt bàn, tổ chức sự kiện hoặc góp ý.
		</p>
	</div>
</section>

<section class="rosa-page-body">
	<div class="rosa-page-body__inner">
		<div class="rosa-contact-grid">
			<div class="rosa-contact-details">
				<div class="rosa-info-card">
					<h3>Địa chỉ</h3>
					<p>
						123 Nguyễn Huệ, Quận 1<br>
						Thành phố Hồ Chí Minh<br>
						Việt Nam
					</p>
				</div>

				<div class="rosa-info-card">
					<h3>Thông tin</h3>
					<p>
						Điện thoại: <a href="tel:+842812345678">028 1234 5678</a><br>
						Email: <a href="mailto:lienhe@senvang.vn">lienhe@senvang.vn</a>
					</p>
				</div>

				<div class="rosa-info-card">
					<h3>Giờ mở cửa</h3>
					<p>
						Thứ 2 – Thứ 6: 10:00 – 22:00<br>
						Thứ 7 – Chủ nhật: 09:00 – 23:00
					</p>
				</div>
			</div>

			<div class="rosa-contact-form-wrap">
				<h2 class="rosa-contact-form__title">Gửi tin nhắn</h2>
				<form class="rosa-form" id="rosa-contact-form" method="post" novalidate>
					<?php wp_nonce_field( 'rosa_contact_submit', 'rosa_contact_nonce' ); ?>
					<input type="hidden" name="action" value="rosa_contact_submit">

					<div class="rosa-form__field">
						<label for="contact-name">Họ tên <span>*</span></label>
						<input type="text" id="contact-name" name="name" required>
					</div>

					<div class="rosa-form__field">
						<label for="contact-email">Email <span>*</span></label>
						<input type="email" id="contact-email" name="email" required>
					</div>

					<div class="rosa-form__field">
						<label for="contact-subject">Tiêu đề</label>
						<input type="text" id="contact-subject" name="subject">
					</div>

					<div class="rosa-form__field">
						<label for="contact-message">Nội dung <span>*</span></label>
						<textarea id="contact-message" name="message" rows="5" required></textarea>
					</div>

					<div class="rosa-form__message" id="rosa-contact-message" role="status" aria-live="polite"></div>

					<p class="rosa-form__action">
						<button type="submit" class="rosa-btn rosa-btn--solid">
							Gửi tin nhắn
						</button>
					</p>
				</form>
			</div>
		</div>
	</div>
</section>
