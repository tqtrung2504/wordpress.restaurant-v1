<?php
/**
 * Rosa feedback page content.
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
		<p class="rosa-section__subtitle">Ý kiến</p>
		<span class="rosa-section__divider" aria-hidden="true">&#10043;</span>
		<h1 class="rosa-page-hero__title">Góp ý</h1>
		<p class="rosa-page-hero__lead">
			Chia sẻ trải nghiệm tại Hanoi Home Taste. Ý kiến của bạn giúp chúng tôi phục vụ tốt hơn.
		</p>
	</div>
</section>

<section class="rosa-page-body">
	<div class="rosa-page-body__inner rosa-page-body__inner--narrow">
		<form class="rosa-form" id="rosa-feedback-form" method="post" novalidate>
			<?php wp_nonce_field( 'rosa_feedback_submit', 'rosa_feedback_nonce' ); ?>
			<input type="hidden" name="action" value="rosa_feedback_submit">

			<div class="rosa-form__field">
				<label for="feedback-rating">Đánh giá <span>*</span></label>
				<div class="rosa-rating" id="feedback-rating">
					<?php for ( $i = 5; $i >= 1; $i-- ) : ?>
						<label class="rosa-rating__star">
							<input type="radio" name="rating" value="<?php echo esc_attr( $i ); ?>" <?php checked( 5, $i ); ?> required>
							<span aria-hidden="true">&#9733;</span>
						</label>
					<?php endfor; ?>
				</div>
			</div>

			<div class="rosa-form__row rosa-form__row--2">
				<div class="rosa-form__field">
					<label for="feedback-name">Họ tên <span>*</span></label>
					<input type="text" id="feedback-name" name="name" required>
				</div>
				<div class="rosa-form__field">
					<label for="feedback-email">Email</label>
					<input type="email" id="feedback-email" name="email">
				</div>
			</div>

			<div class="rosa-form__field">
				<label for="feedback-message">Nội dung góp ý <span>*</span></label>
				<textarea id="feedback-message" name="message" rows="5" required placeholder="Bạn thích điều gì? Chúng tôi có thể cải thiện thế nào?"></textarea>
			</div>

			<div class="rosa-form__message" id="rosa-feedback-message" role="status" aria-live="polite"></div>

			<p class="rosa-form__action">
				<button type="submit" class="rosa-btn rosa-btn--solid">
					Gửi góp ý
				</button>
			</p>
		</form>
	</div>
</section>
