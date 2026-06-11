<?php
/**
 * Rosa homepage parallax section.
 *
 * @package Di Restaurant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section_id    = isset( $args['id'] ) ? $args['id'] : '';
$subtitle      = isset( $args['subtitle'] ) ? $args['subtitle'] : '';
$title         = isset( $args['title'] ) ? $args['title'] : '';
$description   = isset( $args['description'] ) ? $args['description'] : '';
$button_text   = isset( $args['button_text'] ) ? $args['button_text'] : '';
$button_url    = isset( $args['button_url'] ) ? $args['button_url'] : '';
$image         = isset( $args['image'] ) ? $args['image'] : '';
$is_hero       = ! empty( $args['is_hero'] );
$section_class = $is_hero ? 'rosa-section rosa-section--hero' : 'rosa-section rosa-section--content';
?>

<section
	<?php if ( $section_id ) : ?>
	id="<?php echo esc_attr( $section_id ); ?>"
	<?php endif; ?>
	class="<?php echo esc_attr( $section_class ); ?> rosa-animate"
	<?php if ( $image ) : ?>
	data-parallax-image="<?php echo esc_url( $image ); ?>"
	style="background-image: url('<?php echo esc_url( $image ); ?>');"
	<?php endif; ?>
>
	<div class="rosa-section__overlay"></div>
	<div class="rosa-section__inner">
		<div class="rosa-section__content">
			<?php if ( $subtitle ) : ?>
				<p class="rosa-section__subtitle"><?php echo esc_html( $subtitle ); ?></p>
			<?php endif; ?>

			<?php if ( $subtitle && $title ) : ?>
				<span class="rosa-section__divider" aria-hidden="true">&#10043;</span>
			<?php endif; ?>

			<?php if ( $title ) : ?>
				<?php if ( $is_hero ) : ?>
					<h1 class="rosa-section__title"><?php echo esc_html( $title ); ?></h1>
				<?php else : ?>
					<h2 class="rosa-section__title"><?php echo esc_html( $title ); ?></h2>
				<?php endif; ?>
			<?php endif; ?>

			<?php if ( $description ) : ?>
				<div class="rosa-section__description">
					<p><?php echo esc_html( $description ); ?></p>
				</div>
			<?php endif; ?>

			<?php if ( $button_text && $button_url ) : ?>
				<p class="rosa-section__action">
					<a class="rosa-btn" href="<?php echo esc_url( $button_url ); ?>">
						<?php echo esc_html( $button_text ); ?>
					</a>
				</p>
			<?php endif; ?>
		</div>
	</div>
</section>
