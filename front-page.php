<?php

/**

 * Rosa-style front page template.

 *

 * @package Di Restaurant

 */



get_header( 'rosa' );

require_once get_template_directory() . '/inc/core/rosa-vietnamese-data.php';

$img_base   = get_template_directory_uri() . '/assets/images/rosa/';
$brand_name = di_restaurant_rosa_get_brand_name();
$story_text = di_restaurant_rosa_get_story_text();

?>



<main class="rosa-home" id="content">

	<?php

	get_template_part(

		'template-parts/rosa/section',

		null,

		array(

			'id'       => 'welcome',

			'subtitle' => get_theme_mod( 'rosa_hero_subtitle', 'Chào mừng' ),

			'title'    => get_theme_mod( 'rosa_hero_title', $brand_name ),

			'image'    => get_theme_mod( 'rosa_hero_image', $img_base . 'hero.jpg' ),

			'is_hero'  => true,

		)

	);



	get_template_part( 'template-parts/rosa/section', 'video' );



	get_template_part(

		'template-parts/rosa/section',

		null,

		array(

			'id'          => 'our-story',

			'subtitle'    => get_theme_mod( 'rosa_story_subtitle', 'Khám phá' ),

			'title'       => get_theme_mod( 'rosa_story_title', 'Câu chuyện' ),

			'description' => get_theme_mod(

				'rosa_story_description',

				$story_text

			),

			'image'       => get_theme_mod( 'rosa_story_image', $img_base . 'story.jpg' ),

		)

	);



	get_template_part(

		'template-parts/rosa/section',

		null,

		array(

			'id'          => 'menu',

			'subtitle'    => get_theme_mod( 'rosa_menu_subtitle', 'Khám phá' ),

			'title'       => get_theme_mod( 'rosa_menu_title', 'Thực đơn' ),

			'description' => get_theme_mod(

				'rosa_menu_description',

				'Phở, cơm tấm, bún chả, gỏi cuốn và nhiều món Việt được chế biến mỗi ngày từ nguyên liệu tươi. Hãy đến và thưởng thức hương vị đậm đà của ẩm thực quê nhà.'

			),

			'button_text' => get_theme_mod( 'rosa_menu_button', 'Xem thực đơn' ),

			'button_url'  => get_theme_mod( 'rosa_menu_button_url', home_url( '/menu/' ) ),

			'image'       => get_theme_mod( 'rosa_menu_image', $img_base . 'menu.jpg' ),

		)

	);



	get_template_part(

		'template-parts/rosa/section',

		null,

		array(

			'id'          => 'reservations',

			'subtitle'    => get_theme_mod( 'rosa_delight_subtitle', 'Ẩm thực' ),

			'title'       => get_theme_mod( 'rosa_delight_title', 'Tinh hoa' ),

			'description' => get_theme_mod(

				'rosa_delight_description',

				'Chúng tôi mang đến không gian thưởng thức ấm áp, phục vụ tận tâm và hương vị Việt chân thực — để mỗi lần ghé thăm đều là một kỷ niệm đáng nhớ.'

			),

			'button_text' => get_theme_mod( 'rosa_delight_button', 'Đặt bàn ngay' ),

			'button_url'  => get_theme_mod( 'rosa_delight_button_url', home_url( '/reservations/' ) ),

			'image'       => get_theme_mod( 'rosa_delight_image', $img_base . 'delight.jpg' ),

		)

	);

	?>

</main>



<?php

get_footer( 'rosa' );


