<?php
/**
 * Rosa extended features: WooCommerce setup, visitor counter.
 *
 * @package Di Restaurant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Di_Restaurant_Rosa_Features.
 */
class Di_Restaurant_Rosa_Features {

	/**
	 * Instance.
	 *
	 * @var Di_Restaurant_Rosa_Features
	 */
	public static $instance;

	/**
	 * Get instance.
	 *
	 * @return Di_Restaurant_Rosa_Features
	 */
	public static function get_instance() {
		if ( empty( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Constructor.
	 */
	public function __construct() {
		add_action( 'wp', array( $this, 'track_visitor' ) );
		add_action( 'wp', array( $this, 'customize_woocommerce_for_rosa' ) );
		add_action( 'init', array( $this, 'ensure_classic_cart_page' ), 20 );
		add_filter( 'woocommerce_enqueue_styles', array( $this, 'disable_default_woo_styles' ) );
		add_filter( 'render_block', array( $this, 'filter_woocommerce_blocks' ), 10, 2 );
	}

	/**
	 * Disable default WooCommerce styles on Rosa pages.
	 *
	 * @return array
	 */
	public function disable_default_woo_styles( $enqueue_styles ) {
		if ( Di_Restaurant_Rosa::is_rosa_page() ) {
			return array();
		}
		return $enqueue_styles;
	}

	/**
	 * Rosa shop: no product links. Rosa cart: no proceed-to-checkout button.
	 */
	public function customize_woocommerce_for_rosa() {
		if ( ! class_exists( 'WooCommerce' ) || ! Di_Restaurant_Rosa::is_rosa_page() ) {
			return;
		}

		if ( is_shop() || is_product_category() || is_product_tag() ) {
			remove_action( 'woocommerce_before_shop_loop_item', 'woocommerce_template_loop_product_link_open', 10 );
			remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_product_link_close', 5 );
		}

		if ( is_cart() ) {
			remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 );
		}
	}

	/**
	 * Use classic cart shortcode so Rosa cart layout/CSS applies.
	 */
	public function ensure_classic_cart_page() {
		if ( get_option( 'rosa_cart_classic_shortcode' ) || ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$cart_page_id = wc_get_page_id( 'cart' );
		if ( $cart_page_id <= 0 ) {
			return;
		}

		$page = get_post( $cart_page_id );
		if ( ! $page || false !== strpos( $page->post_content, '[woocommerce_cart]' ) ) {
			update_option( 'rosa_cart_classic_shortcode', '1', false );
			return;
		}

		wp_update_post(
			array(
				'ID'           => $cart_page_id,
				'post_content' => '[woocommerce_cart]',
			)
		);
		update_option( 'rosa_cart_classic_shortcode', '1', false );
	}

	/**
	 * Hide block-based checkout button on Rosa cart (fallback).
	 *
	 * @param string $block_content Block HTML.
	 * @param array  $block         Block data.
	 * @return string
	 */
	public function filter_woocommerce_blocks( $block_content, $block ) {
		if ( empty( $block['blockName'] ) || ! Di_Restaurant_Rosa::is_rosa_page() || ! is_cart() ) {
			return $block_content;
		}

		if ( 'woocommerce/proceed-to-checkout' === $block['blockName'] ) {
			return '';
		}

		return $block_content;
	}

	/**
	 * Track visitor count once per session.
	 */
	public function track_visitor() {
		if ( is_admin() || wp_doing_ajax() || wp_doing_cron() ) {
			return;
		}

		if ( isset( $_COOKIE['rosa_visited'] ) ) {
			return;
		}

		$count = (int) get_option( 'rosa_visitor_count', 0 );
		update_option( 'rosa_visitor_count', $count + 1, false );

		if ( ! headers_sent() ) {
			setcookie( 'rosa_visited', '1', time() + DAY_IN_SECONDS, COOKIEPATH, COOKIE_DOMAIN, is_ssl(), true );
		}
	}

	/**
	 * Get formatted visitor count.
	 *
	 * @return string
	 */
	public static function get_visitor_count() {
		return number_format_i18n( (int) get_option( 'rosa_visitor_count', 0 ) );
	}

	/**
	 * Activate WooCommerce if present.
	 *
	 * @return bool
	 */
	public function maybe_activate_woocommerce() {
		if ( ! function_exists( 'activate_plugin' ) ) {
			require_once ABSPATH . 'wp-admin/includes/plugin.php';
		}

		$plugin = 'woocommerce/woocommerce.php';

		if ( ! file_exists( WP_PLUGIN_DIR . '/woocommerce/woocommerce.php' ) ) {
			return false;
		}

		if ( ! is_plugin_active( $plugin ) ) {
			activate_plugin( $plugin );
		}

		return class_exists( 'WooCommerce' );
	}

	/**
	 * Setup WooCommerce pages and sample products.
	 */
	public function setup_woocommerce() {
		if ( ! $this->maybe_activate_woocommerce() ) {
			return;
		}

		if ( class_exists( 'WC_Install' ) ) {
			WC_Install::create_pages();
		}

		$this->replace_vietnamese_products();

		update_option( 'woocommerce_currency', 'VND' );
		update_option( 'woocommerce_currency_pos', 'right_space' );
		update_option( 'woocommerce_price_thousand_sep', '.' );
		update_option( 'woocommerce_price_decimal_sep', ',' );
		update_option( 'woocommerce_price_num_decimals', '0' );
		update_option( 'woocommerce_enable_guest_checkout', 'yes' );
		update_option( 'woocommerce_coming_soon', 'no' );
		update_option( 'woocommerce_store_pages_only', 'no' );

		$shop_page_id = wc_get_page_id( 'shop' );
		if ( $shop_page_id > 0 ) {
			wp_update_post(
				array(
					'ID'         => $shop_page_id,
					'post_title' => 'Cửa hàng',
				)
			);
		}

		$cart_page_id = wc_get_page_id( 'cart' );
		if ( $cart_page_id > 0 ) {
			wp_update_post(
				array(
					'ID'           => $cart_page_id,
					'post_title'   => 'Giỏ hàng',
					'post_content' => '[woocommerce_cart]',
				)
			);
		}
	}

	/**
	 * Replace shop products with Vietnamese menu items.
	 */
	public function replace_vietnamese_products() {
		if ( ! class_exists( 'WooCommerce' ) ) {
			return;
		}

		$existing = get_posts(
			array(
				'post_type'      => 'product',
				'post_status'    => 'any',
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);

		foreach ( $existing as $product_id ) {
			wp_delete_post( $product_id, true );
		}

		$products = di_restaurant_rosa_get_vietnamese_products();

		foreach ( $products as $product_data ) {
			$this->create_product( $product_data );
		}
	}

	/**
	 * Create a WooCommerce product.
	 *
	 * @param array $data Product data.
	 */
	private function create_product( $data ) {
		$product = new WC_Product_Simple();
		$product->set_name( $data['name'] );
		$product->set_slug( $data['slug'] );
		$product->set_regular_price( $data['price'] );
		$product->set_description( $data['desc'] );
		$product->set_short_description( $data['desc'] );
		$product->set_status( 'publish' );
		$product->set_catalog_visibility( 'visible' );
		$product->set_stock_status( 'instock' );

		$product_id = $product->save();

		if ( $product_id && ! empty( $data['image'] ) ) {
			$this->attach_product_image_from_url( $product_id, $data['image'], $data['name'] );
		}
	}

	/**
	 * Attach external image to product.
	 *
	 * @param int    $product_id Product ID.
	 * @param string $image_url Image URL.
	 * @param string $title Image title.
	 */
	private function attach_product_image_from_url( $product_id, $image_url, $title ) {
		if ( ! function_exists( 'media_sideload_image' ) ) {
			require_once ABSPATH . 'wp-admin/includes/media.php';
			require_once ABSPATH . 'wp-admin/includes/file.php';
			require_once ABSPATH . 'wp-admin/includes/image.php';
		}

		$old_thumb = get_post_thumbnail_id( $product_id );
		if ( $old_thumb ) {
			wp_delete_attachment( $old_thumb, true );
		}

		$local_path = str_replace( get_template_directory_uri(), get_template_directory(), $image_url );

		if ( file_exists( $local_path ) ) {
			$upload_dir = wp_upload_dir();
			$filename   = wp_unique_filename( $upload_dir['path'], basename( $local_path ) );
			$upload     = wp_upload_bits( $filename, null, file_get_contents( $local_path ) );
		} else {
			$attachment_id = media_sideload_image( $image_url, $product_id, $title, 'id' );
			if ( ! is_wp_error( $attachment_id ) ) {
				set_post_thumbnail( $product_id, $attachment_id );
			}
			return;
		}

		if ( empty( $upload['error'] ) ) {
			$wp_filetype = wp_check_filetype( basename( $local_path ), null );
			$attachment  = array(
				'post_mime_type' => $wp_filetype['type'],
				'post_title'     => sanitize_file_name( $title ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			);
			$attach_id   = wp_insert_attachment( $attachment, $upload['file'], $product_id );
			require_once ABSPATH . 'wp-admin/includes/image.php';
			$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
			wp_update_attachment_metadata( $attach_id, $attach_data );
			set_post_thumbnail( $product_id, $attach_id );
		}
	}
}

Di_Restaurant_Rosa_Features::get_instance();
