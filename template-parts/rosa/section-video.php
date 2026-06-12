<?php
/**
 * Rosa intro video section.
 *
 * @package Di Restaurant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$video_url = get_theme_mod(
	'rosa_intro_video',
	get_template_directory_uri() . '/assets/videos/intro.mp4'
);
$poster_url = get_theme_mod(
	'rosa_intro_video_poster',
	get_template_directory_uri() . '/assets/images/rosa/hero.jpg'
);
?>

<section class="rosa-video-section rosa-animate" id="intro-video">
	<div class="rosa-video-section__inner">
		<div class="rosa-video-section__header">
			<p class="rosa-section__subtitle">Trải nghiệm</p>
			<span class="rosa-section__divider" aria-hidden="true">&#10043;</span>
			<h2 class="rosa-video-section__title">Giới thiệu Hanoi Home Taste</h2>
			<p class="rosa-video-section__lead">
				Video ngắn giới thiệu không gian, bếp và tinh thần ẩm thực Hà Nội tại nhà hàng.
			</p>
		</div>

		<div class="rosa-video-section__player rosa-animate rosa-animate--delay">
			<video
				class="rosa-video"
				controls
				playsinline
				preload="metadata"
				poster="<?php echo esc_url( $poster_url ); ?>"
				width="960"
				height="540"
			>
				<source src="<?php echo esc_url( $video_url ); ?>" type="video/mp4">
				Trình duyệt của bạn không hỗ trợ phát video.
			</video>
		</div>
	</div>
</section>

