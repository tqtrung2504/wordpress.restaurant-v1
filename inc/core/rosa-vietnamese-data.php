<?php
/**
 * Vietnamese content data for Rosa site.
 *
 * @package Di Restaurant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Restaurant brand name.
 *
 * @return string
 */
function di_restaurant_rosa_get_brand_name() {
	return 'Hanoi Home Taste';
}

/**
 * Restaurant story text.
 *
 * @return string
 */
function di_restaurant_rosa_get_story_text() {
	return 'Tại Hanoi Home Taste, chúng tôi tin rằng mỗi món ăn đều mang trong mình một câu chuyện riêng. Đó có thể là hương vị quen thuộc của những bữa cơm gia đình, là ký ức về những con phố cổ nhộn nhịp hay là nét thanh lịch đặc trưng của người Hà Nội. Chúng tôi luôn ưu tiên sử dụng những nguyên liệu tươi ngon và giữ gìn cách chế biến truyền thống để mỗi món ăn vẫn giữ được hương vị nguyên bản vốn có. Bởi chúng tôi hiểu rằng giá trị của ẩm thực không chỉ nằm ở sự ngon miệng mà còn ở cảm xúc mà nó mang lại.';
}

/**
 * Get Vietnamese menu sections.
 *
 * @return array
 */
function di_restaurant_rosa_get_vietnamese_menu() {
	$img = get_template_directory_uri() . '/assets/images/rosa/menu/';

	return array(
		array(
			'title' => 'Khai vị',
			'items' => array(
				array(
					'name'        => 'Gỏi Cuốn Tôm Thịt',
					'description' => 'Tôm tươi, thịt luộc, bún và rau thơm cuộn bánh tráng, kèm nước chấm cay chua ngọt.',
					'price'       => '75000',
					'image'       => $img . 'goi-cuon.jpg',
				),
				array(
					'name'        => 'Nộm Bò Khô ',
					'description' => 'Đu đủ xanh bào sợi, bò khô cay nồng, rau thơm và lạc rang sốt chua ngọt.',
					'price'       => '55000',
					'image'       => $img . 'cha-gio.jpg',
				),
				array(
					'name'        => 'Gỏi đu đủ khô bò',
					'description' => 'Đu đủ xanh, khô bò, đậu phộng rang, rau thơm, sốt mắm ruốc.',
					'price'       => '65000',
					'image'       => $img . 'goi-du-du.jpg',
				),
			),
		),
		array(
			'title' => 'Món chính',
			'items' => array(
				array(
					'name'        => 'Bún Thang',
					'description' => 'Sự kết hợp tinh tế từ lườn gà xé, giò lụa, trứng tráng thái chỉ và nước dùng gà trong vắt.',
					'price'       => '75000',
					'image'       => $img . 'pho-bo.jpg',
				),
				array(
					'name'        => 'Phở Bò Hà Nội',
					'description' => 'Bánh phở dẻo, thịt bò tươi thái mỏng hòa cùng nước dùng ninh xương bò thanh ngọt.',
					'price'       => '70000',
					'image'       => $img . 'com-tam.jpg',
				),
				array(
					'name'        => 'Bún Chả Hà Nội',
					'description' => 'Chả viên và chả miếng nướng than hoa xém cạnh, ngập trong nước mắm đu đủ chua ngọt.',
					'price'       => '68000',
					'image'       => $img . 'bun-cha.jpg',
				),
			),
		),
		array(
			'title' => 'Tráng miệng',
			'items' => array(
				array(
					'name'        => 'Chè Khúc Bạch',
					'description' => 'Khúc bạch sữa béo ngậy, hạnh nhân nướng thơm bùi kèm quả vải và trân châu trắng.',
					'price'       => '30000',
					'image'       => $img . 'che-ba-mau.jpg',
				),
				array(
					'name'        => 'Kem Tràng Tiền',
					'description' => 'Kem cây hoặc kem viên truyền thống với các hương vị đặc trưng: cốm, dừa, sữa dừa.',
					'price'       => '28000',
					'image'       => $img . 'banh-flan.jpg',
				),
			),
		),
		array(
			'title' => 'Đồ uống',
			'items' => array(
				array(
					'name'        => 'Trà Chanh',
					'description' => 'Trà xanh mộc thanh mát hòa quyện chanh tươi, mật ong tự nhiên và đá lạnh',
					'price'       => '25000',
					'image'       => $img . 'tra-da.jpg',
				),
				array(
					'name'        => 'Cà phê sữa đá',
					'description' => 'Cà phê phin truyền thống, sữa đặc, đá viên.',
					'price'       => '35000',
					'image'       => $img . 'ca-phe-sua.jpg',
				),
				array(
					'name'        => 'Trà đá',
					'description' => 'Giá bình dân, trà xanh mộc thanh mát hòa với đá lạnh',
					'price'       => '10000',
					'image'       => $img . 'nuoc-mia.jpg',
				),
			),
		),
	);
}

/**
 * Format VND price.
 *
 * @param string|int $amount Amount.
 * @return string
 */
function di_restaurant_rosa_format_vnd( $amount ) {
	return number_format( (int) $amount, 0, ',', '.' ) . ' ₫';
}

/**
 * Build menu text for AI chatbot system prompt.
 *
 * @return string
 */
function di_restaurant_rosa_get_menu_prompt_context() {
	$sections = di_restaurant_rosa_get_vietnamese_menu();
	$lines    = array( 'THỰC ĐƠN ' . strtoupper( di_restaurant_rosa_get_brand_name() ) . ':' );

	foreach ( $sections as $section ) {
		$lines[] = "\n## {$section['title']}";
		foreach ( $section['items'] as $item ) {
			$price   = di_restaurant_rosa_format_vnd( $item['price'] );
			$lines[] = "- {$item['name']} ({$price}): {$item['description']}";
		}
	}

	$lines[] = "\nGiờ mở cửa: 10:00 - 22:00 hàng ngày.";
	$lines[] = 'Địa chỉ: 123 Đường Ẩm Thực, Quận 1, TP. Hồ Chí Minh.';
	$lines[] = 'Điện thoại: 0901 234 567.';
	$lines[] = 'Đặt bàn tại: ' . home_url( '/reservations/' );

	return implode( "\n", $lines );
}

/**
 * Get WooCommerce Vietnamese products (synced from menu data).
 *
 * @return array
 */
function di_restaurant_rosa_get_vietnamese_products() {
	$slug_map = array(
		'Gỏi Cuốn Tôm Thịt' => 'goi-cuon-tom-thit',
		'Nộm Bò Khô'        => 'nom-bo-kho',
		'Gỏi đu đủ khô bò'  => 'goi-du-du-kho-bo',
		'Bún Thang'         => 'bun-thang',
		'Phở Bò Hà Nội'     => 'pho-bo-ha-noi',
		'Bún Chả Hà Nội'    => 'bun-cha-ha-noi',
		'Chè Khúc Bạch'     => 'che-khuc-bach',
		'Kem Tràng Tiền'    => 'kem-trang-tien',
		'Trà Chanh'         => 'tra-chanh',
		'Cà phê sữa đá'     => 'ca-phe-sua-da',
		'Trà đá'            => 'tra-da',
	);

	$products = array();

	foreach ( di_restaurant_rosa_get_vietnamese_menu() as $section ) {
		foreach ( $section['items'] as $item ) {
			$slug = isset( $slug_map[ $item['name'] ] ) ? $slug_map[ $item['name'] ] : sanitize_title( $item['name'] );

			$products[] = array(
				'name'  => $item['name'],
				'slug'  => $slug,
				'price' => $item['price'],
				'image' => $item['image'],
				'desc'  => $item['description'],
			);
		}
	}

	return $products;
}
