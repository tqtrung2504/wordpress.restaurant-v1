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
					'name'        => 'Gỏi cuốn tôm thịt',
					'description' => 'Bánh tráng cuốn tôm, thịt luộc, bún, rau thơm, chấm tương đậu.',
					'price'       => '45000',
					'image'       => $img . 'goi-cuon.jpg',
				),
				array(
					'name'        => 'Chả giò rế',
					'description' => 'Chả giò giòn rụm, nhân tôm thịt, ăn kèm rau sống và nước mắm chua ngọt.',
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
					'name'        => 'Phở bò tái',
					'description' => 'Nước dùng ninh xương 12 giờ, bánh phở tươi, thịt bò tái, hành lá, ngò gai.',
					'price'       => '75000',
					'image'       => $img . 'pho-bo.jpg',
				),
				array(
					'name'        => 'Cơm tấm sườn bì chả',
					'description' => 'Cơm tấm dẻo thơm, sườn nướng, bì, chả trứng, đồ chua, nước mắm.',
					'price'       => '70000',
					'image'       => $img . 'com-tam.jpg',
				),
				array(
					'name'        => 'Bún chả Hà Nội',
					'description' => 'Chả nướng than hoa, bún tươi, rau sống, nước mắm pha chua ngọt.',
					'price'       => '68000',
					'image'       => $img . 'bun-cha.jpg',
				),
			),
		),
		array(
			'title' => 'Tráng miệng',
			'items' => array(
				array(
					'name'        => 'Chè ba màu',
					'description' => 'Đậu xanh, đậu đỏ, thạch dừa, nước cốt dừa, đá viên.',
					'price'       => '30000',
					'image'       => $img . 'che-ba-mau.jpg',
				),
				array(
					'name'        => 'Bánh flan',
					'description' => 'Bánh flan mềm mịn, caramel thơm, phục vụ lạnh.',
					'price'       => '28000',
					'image'       => $img . 'banh-flan.jpg',
				),
			),
		),
		array(
			'title' => 'Đồ uống',
			'items' => array(
				array(
					'name'        => 'Trà đá',
					'description' => 'Trà xanh pha đậm, phục vụ đá lạnh.',
					'price'       => '10000',
					'image'       => $img . 'tra-da.jpg',
				),
				array(
					'name'        => 'Cà phê sữa đá',
					'description' => 'Cà phê phin truyền thống, sữa đặc, đá viên.',
					'price'       => '35000',
					'image'       => $img . 'ca-phe-sua.jpg',
				),
				array(
					'name'        => 'Nước mía',
					'description' => 'Nước mía ép tươi, thêm chanh tươi.',
					'price'       => '25000',
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
 * Get WooCommerce Vietnamese products.
 *
 * @return array
 */
function di_restaurant_rosa_get_vietnamese_products() {
	$img = get_template_directory_uri() . '/assets/images/rosa/menu/';

	return array(
		array(
			'name'  => 'Phở bò tái',
			'slug'  => 'pho-bo-tai',
			'price' => '75000',
			'image' => $img . 'pho-bo.jpg',
			'desc'  => 'Nước dùng ninh xương 12 giờ, bánh phở tươi, thịt bò tái.',
		),
		array(
			'name'  => 'Cơm tấm sườn bì chả',
			'slug'  => 'com-tam-suon-bi-cha',
			'price' => '70000',
			'image' => $img . 'com-tam.jpg',
			'desc'  => 'Cơm tấm dẻo thơm, sườn nướng, bì, chả trứng.',
		),
		array(
			'name'  => 'Bún chả Hà Nội',
			'slug'  => 'bun-cha-ha-noi',
			'price' => '68000',
			'image' => $img . 'bun-cha.jpg',
			'desc'  => 'Chả nướng than hoa, bún tươi, rau sống.',
		),
		array(
			'name'  => 'Gỏi cuốn tôm thịt',
			'slug'  => 'goi-cuon-tom-thit',
			'price' => '45000',
			'image' => $img . 'goi-cuon.jpg',
			'desc'  => 'Bánh tráng cuốn tôm, thịt, rau thơm.',
		),
		array(
			'name'  => 'Cà phê sữa đá',
			'slug'  => 'ca-phe-sua-da',
			'price' => '35000',
			'image' => $img . 'ca-phe-sua.jpg',
			'desc'  => 'Cà phê phin truyền thống, sữa đặc.',
		),
		array(
			'name'  => 'Chè ba màu',
			'slug'  => 'che-ba-mau',
			'price' => '30000',
			'image' => $img . 'che-ba-mau.jpg',
			'desc'  => 'Đậu xanh, đậu đỏ, thạch dừa, nước cốt dừa.',
		),
	);
}
