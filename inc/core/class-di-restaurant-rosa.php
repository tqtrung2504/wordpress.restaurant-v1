<?php
/**
 * Rosa Lite style homepage and demo content.
 *
 * @package Di Restaurant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Di_Restaurant_Rosa.
 */
class Di_Restaurant_Rosa {

	/**
	 * Instance object.
	 *
	 * @var Di_Restaurant_Rosa
	 */
	public static $instance;

	/**
	 * Get instance.
	 *
	 * @return Di_Restaurant_Rosa
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
		add_action( 'after_setup_theme', array( $this, 'register_menus' ), 20 );
		add_action( 'init', array( $this, 'register_submission_post_type' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 20 );
		add_action( 'init', array( $this, 'register_kirki_options' ), 20 );
		add_action( 'after_switch_theme', array( $this, 'maybe_setup_demo' ) );
		add_action( 'admin_menu', array( $this, 'register_admin_page' ) );
		add_action( 'admin_post_di_restaurant_import_rosa_demo', array( $this, 'handle_demo_import' ) );
		add_action( 'admin_notices', array( $this, 'demo_notice' ) );

		add_action( 'wp_ajax_rosa_feedback_submit', array( $this, 'handle_feedback_submit' ) );
		add_action( 'wp_ajax_nopriv_rosa_feedback_submit', array( $this, 'handle_feedback_submit' ) );
		add_action( 'wp_ajax_rosa_contact_submit', array( $this, 'handle_contact_submit' ) );
		add_action( 'wp_ajax_nopriv_rosa_contact_submit', array( $this, 'handle_contact_submit' ) );
		add_action( 'wp_ajax_rosa_reservation_submit', array( $this, 'handle_reservation_submit' ) );
		add_action( 'wp_ajax_nopriv_rosa_reservation_submit', array( $this, 'handle_reservation_submit' ) );

		add_filter( 'manage_rosa_submission_posts_columns', array( $this, 'submission_columns' ) );
		add_action( 'manage_rosa_submission_posts_custom_column', array( $this, 'submission_column_content' ), 10, 2 );
	}

	/**
	 * Whether the current request should use Rosa assets/layout.
	 *
	 * @return bool
	 */
	public static function is_rosa_page() {
		if ( is_front_page() || is_page( array( 'menu', 'reservations', 'contact', 'feedback' ) ) ) {
			return true;
		}

		if ( class_exists( 'WooCommerce' ) ) {
			return is_shop() || is_product_category() || is_product_tag() || is_product() || is_cart() || is_checkout() || is_account_page();
		}

		return false;
	}

	/**
	 * Register submission storage post type.
	 */
	public function register_submission_post_type() {
		register_post_type(
			'rosa_submission',
			array(
				'labels'              => array(
					'name'          => __( 'Rosa Submissions', 'di-restaurant' ),
					'singular_name' => __( 'Rosa Submission', 'di-restaurant' ),
					'menu_name'     => __( 'Rosa Submissions', 'di-restaurant' ),
				),
				'public'              => false,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'menu_icon'           => 'dashicons-email-alt',
				'capability_type'     => 'post',
				'capabilities'        => array(
					'create_posts' => 'do_not_allow',
				),
				'map_meta_cap'        => true,
				'supports'            => array( 'title', 'editor' ),
				'exclude_from_search' => true,
			)
		);
	}

	/**
	 * Register Rosa menus.
	 */
	public function register_menus() {
		register_nav_menus(
			array(
				'footer' => __( 'Footer Menu', 'di-restaurant' ),
			)
		);
	}

	/**
	 * Enqueue Rosa assets on the front page.
	 */
	public function enqueue_assets() {
		if ( ! self::is_rosa_page() ) {
			return;
		}

		wp_dequeue_style( 'bootstrap' );
		wp_dequeue_style( 'font-awesome' );
		wp_dequeue_style( 'di-restaurant-style-default' );
		wp_dequeue_style( 'di-restaurant-style-core' );
		wp_dequeue_style( 'di-restaurant-style-woo' );
		wp_dequeue_script( 'bootstrap' );
		wp_dequeue_script( 'di-restaurant-script' );
		wp_dequeue_script( 'di-restaurant-mainmenu' );
		wp_dequeue_script( 'di-restaurant-backtotop' );
		wp_dequeue_script( 'di-restaurant-loadicon' );

		wp_enqueue_style(
			'di-restaurant-rosa',
			get_template_directory_uri() . '/assets/css/rosa.css',
			array(),
			DI_RESTAURANT_VERSION,
			'all'
		);

		if ( class_exists( 'WooCommerce' ) && ( is_shop() || is_product() || is_cart() || is_checkout() || is_account_page() ) ) {
			wp_enqueue_style(
				'di-restaurant-rosa-woo',
				get_template_directory_uri() . '/assets/css/rosa-woo.css',
				array( 'di-restaurant-rosa' ),
				DI_RESTAURANT_VERSION,
				'all'
			);
		}

		wp_enqueue_script(
			'di-restaurant-rosa',
			get_template_directory_uri() . '/assets/js/rosa.js',
			array( 'jquery' ),
			DI_RESTAURANT_VERSION,
			true
		);

		wp_localize_script(
			'di-restaurant-rosa',
			'diRosaForms',
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'nonce'   => wp_create_nonce( 'rosa_forms_ajax' ),
			)
		);
	}

	/**
	 * Register customizer options.
	 */
	public function register_kirki_options() {
		if ( ! class_exists( 'Kirki' ) ) {
			return;
		}

		Kirki::add_section(
			'rosa_home_options',
			array(
				'title'    => esc_html__( 'Rosa Homepage', 'di-restaurant' ),
				'priority' => 12,
			)
		);

		$fields = array(
			array( 'rosa_hero_subtitle', __( 'Hero Subtitle', 'di-restaurant' ), __( 'Welcome', 'di-restaurant' ) ),
			array( 'rosa_hero_title', __( 'Hero Title', 'di-restaurant' ), __( 'The Rosa', 'di-restaurant' ) ),
			array( 'rosa_story_subtitle', __( 'Story Subtitle', 'di-restaurant' ), __( 'Discover', 'di-restaurant' ) ),
			array( 'rosa_story_title', __( 'Story Title', 'di-restaurant' ), __( 'Our Story', 'di-restaurant' ) ),
			array(
				'rosa_story_description',
				__( 'Story Description', 'di-restaurant' ),
				__( 'Rosa is a restaurant, bar and coffee roastery located on a busy corner site in Farringdon\'s Exmouth Market. With glazed frontage on two sides of the building, overlooking the market and a bustling London intersection.', 'di-restaurant' ),
				'textarea',
			),
			array( 'rosa_story_button', __( 'Story Button Text', 'di-restaurant' ), __( 'About Us', 'di-restaurant' ) ),
			array( 'rosa_menu_subtitle', __( 'Menu Subtitle', 'di-restaurant' ), __( 'Discover', 'di-restaurant' ) ),
			array( 'rosa_menu_title', __( 'Menu Title', 'di-restaurant' ), __( 'Menu', 'di-restaurant' ) ),
			array(
				'rosa_menu_description',
				__( 'Menu Description', 'di-restaurant' ),
				__( 'For those with pure food indulgence in mind, come next door and sate your desires with our ever changing internationally and seasonally inspired small plates. We love food, lots of different food, just like you.', 'di-restaurant' ),
				'textarea',
			),
			array( 'rosa_menu_button', __( 'Menu Button Text', 'di-restaurant' ), __( 'View The Full Menu', 'di-restaurant' ) ),
			array( 'rosa_delight_subtitle', __( 'Delight Subtitle', 'di-restaurant' ), __( 'Culinary', 'di-restaurant' ) ),
			array( 'rosa_delight_title', __( 'Delight Title', 'di-restaurant' ), __( 'Delight', 'di-restaurant' ) ),
			array(
				'rosa_delight_description',
				__( 'Delight Description', 'di-restaurant' ),
				__( 'We promise an intimate and relaxed dining experience that offers something different to local and foreign patrons and ensures you enjoy a memorable food experience every time.', 'di-restaurant' ),
				'textarea',
			),
			array( 'rosa_delight_button', __( 'Delight Button Text', 'di-restaurant' ), __( 'Make a Reservation', 'di-restaurant' ) ),
		);

		foreach ( $fields as $field ) {
			$type = isset( $field[3] ) ? $field[3] : 'text';

			Kirki::add_field(
				'di_restaurant_config',
				array(
					'type'     => $type,
					'settings' => $field[0],
					'label'    => $field[1],
					'section'  => 'rosa_home_options',
					'default'  => $field[2],
				)
			);
		}

		$image_fields = array(
			'rosa_hero_image'   => __( 'Hero Background Image', 'di-restaurant' ),
			'rosa_story_image'  => __( 'Story Background Image', 'di-restaurant' ),
			'rosa_menu_image'   => __( 'Menu Background Image', 'di-restaurant' ),
			'rosa_delight_image'=> __( 'Delight Background Image', 'di-restaurant' ),
		);

		foreach ( $image_fields as $setting => $label ) {
			Kirki::add_field(
				'di_restaurant_config',
				array(
					'type'     => 'image',
					'settings' => $setting,
					'label'    => $label,
					'section'  => 'rosa_home_options',
					'default'  => '',
				)
			);
		}
	}

	/**
	 * Setup demo on first theme activation.
	 */
	public function maybe_setup_demo() {
		if ( get_option( 'di_restaurant_rosa_demo_imported' ) ) {
			return;
		}

		$this->import_demo_content();
	}

	/**
	 * Admin notice for demo import.
	 */
	public function demo_notice() {
		if ( get_option( 'di_restaurant_rosa_demo_imported' ) || ! current_user_can( 'manage_options' ) ) {
			return;
		}

		$url = wp_nonce_url(
			admin_url( 'admin-post.php?action=di_restaurant_import_rosa_demo' ),
			'di_restaurant_import_rosa_demo'
		);
		?>
		<div class="notice notice-info is-dismissible">
			<p>
				<?php esc_html_e( 'Import Rosa Lite demo content (pages, menu, homepage settings) to complete your restaurant site.', 'di-restaurant' ); ?>
				<a class="button button-primary" href="<?php echo esc_url( $url ); ?>" style="margin-left: 10px;">
					<?php esc_html_e( 'Import Rosa Demo', 'di-restaurant' ); ?>
				</a>
			</p>
		</div>
		<?php
	}

	/**
	 * Register admin page.
	 */
	public function register_admin_page() {
		add_theme_page(
			__( 'Rosa Demo Import', 'di-restaurant' ),
			__( 'Rosa Demo Import', 'di-restaurant' ),
			'manage_options',
			'di-restaurant-rosa-demo',
			array( $this, 'render_admin_page' )
		);
	}

	/**
	 * Render admin page.
	 */
	public function render_admin_page() {
		$url = wp_nonce_url(
			admin_url( 'admin-post.php?action=di_restaurant_import_rosa_demo' ),
			'di_restaurant_import_rosa_demo'
		);
		?>
		<div class="wrap">
			<h1><?php esc_html_e( 'Rosa Demo Import', 'di-restaurant' ); ?></h1>
			<p><?php esc_html_e( 'This will create demo pages, navigation menus, and homepage settings similar to the Rosa Lite demo.', 'di-restaurant' ); ?></p>
			<p><a class="button button-primary" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Import Rosa Demo Content', 'di-restaurant' ); ?></a></p>
		</div>
		<?php
	}

	/**
	 * Handle demo import request.
	 */
	public function handle_demo_import() {
		if ( ! current_user_can( 'manage_options' ) ) {
			wp_die( esc_html__( 'You do not have permission to import demo content.', 'di-restaurant' ) );
		}

		check_admin_referer( 'di_restaurant_import_rosa_demo' );

		$this->import_demo_content();

		wp_safe_redirect(
			add_query_arg(
				array(
					'page'             => 'di-restaurant-rosa-demo',
					'rosa_demo_import' => '1',
				),
				admin_url( 'themes.php' )
			)
		);
		exit;
	}

	/**
	 * Apply Vietnamese site name, texts, and theme mods.
	 */
	public function apply_vietnamese_locale() {
		$img_base = get_template_directory_uri() . '/assets/images/rosa/';

		$theme_mods = array(
			'rosa_hero_subtitle'         => 'Chào mừng',
			'rosa_hero_title'            => 'Nhà Hàng Sen Vàng',
			'rosa_hero_image'            => $img_base . 'hero.jpg',
			'rosa_story_subtitle'        => 'Khám phá',
			'rosa_story_title'           => 'Câu chuyện',
			'rosa_story_description'     => 'Nhà Hàng Sen Vàng ra đời từ tình yêu ẩm thực Việt. Chúng tôi tôn vinh hương vị truyền thống, nguyên liệu tươi sạch và không gian ấm cúng như ở nhà.',
			'rosa_story_button'          => 'Về chúng tôi',
			'rosa_menu_subtitle'         => 'Khám phá',
			'rosa_menu_title'            => 'Thực đơn',
			'rosa_menu_description'      => 'Phở, cơm tấm, bún chả, gỏi cuốn và nhiều món Việt được chế biến mỗi ngày từ nguyên liệu tươi.',
			'rosa_menu_button'           => 'Xem thực đơn',
			'rosa_delight_subtitle'      => 'Ẩm thực',
			'rosa_delight_title'         => 'Tinh hoa',
			'rosa_delight_description'   => 'Không gian ấm áp, phục vụ tận tâm và hương vị Việt chân thực — để mỗi lần ghé thăm đều là kỷ niệm đáng nhớ.',
			'rosa_delight_button'        => 'Đặt bàn ngay',
			'rosa_story_image'           => $img_base . 'story.jpg',
			'rosa_menu_image'            => $img_base . 'menu.jpg',
			'rosa_delight_image'         => $img_base . 'delight.jpg',
			'rosa_story_button_url'      => home_url( '/our-story/' ),
			'rosa_menu_button_url'       => home_url( '/menu/' ),
			'rosa_delight_button_url'    => home_url( '/reservations/' ),
		);

		foreach ( $theme_mods as $key => $value ) {
			set_theme_mod( $key, $value );
		}

		update_option( 'blogname', 'Nhà Hàng Sen Vàng' );
		update_option( 'blogdescription', 'Ẩm thực Việt đích thực' );
		update_option( 'WPLANG', 'vi' );
	}

	/**
	 * Import Rosa demo pages, menus, and settings.
	 */
	public function import_demo_content() {
		$this->apply_vietnamese_locale();

		update_option( 'blogname', 'Nhà Hàng Sen Vàng' );
		update_option( 'blogdescription', 'Ẩm thực Việt đích thực' );

		$pages = array(
			'our-story'    => array(
				'title'   => 'Câu chuyện',
				'content' => 'Nhà Hàng Sen Vàng ra đời từ tình yêu ẩm thực Việt. Chúng tôi tôn vinh hương vị truyền thống, nguyên liệu tươi sạch và không gian ấm cúng như ở nhà.',
			),
			'menu'         => array(
				'title'   => 'Thực đơn',
				'content' => 'Khám phá thực đơn món Việt đặc sắc: phở, cơm tấm, bún chả, gỏi cuốn và nhiều món được chế biến mỗi ngày.',
			),
			'reservations' => array(
				'title'   => 'Đặt bàn',
				'content' => 'Đặt bàn trước để có trải nghiệm ẩm thực Việt trọn vẹn trong không gian ấm áp.',
			),
			'contact'      => array(
				'title'   => 'Liên hệ',
				'content' => 'Liên hệ đội ngũ Nhà Hàng Sen Vàng để đặt bàn, tổ chức sự kiện hoặc các thắc mắc khác.',
			),
			'feedback'     => array(
				'title'   => 'Góp ý',
				'content' => 'Chia sẻ trải nghiệm của bạn tại Nhà Hàng Sen Vàng.',
			),
		);

		$page_ids = array();

		foreach ( $pages as $slug => $page ) {
			$existing = get_page_by_path( $slug );

			if ( $existing ) {
				wp_update_post(
					array(
						'ID'           => $existing->ID,
						'post_title'   => $page['title'],
						'post_content' => $page['content'],
					)
				);
				$page_ids[ $slug ] = $existing->ID;
				continue;
			}

			$parent_id = 0;
			if ( ! empty( $page['parent'] ) && ! empty( $page_ids[ $page['parent'] ] ) ) {
				$parent_id = $page_ids[ $page['parent'] ];
			}

			$page_ids[ $slug ] = wp_insert_post(
				array(
					'post_title'   => $page['title'],
					'post_name'    => $slug,
					'post_content' => $page['content'],
					'post_status'  => 'publish',
					'post_type'    => 'page',
					'post_parent'  => $parent_id,
				)
			);
		}

		$home_page = get_page_by_path( 'home' );
		if ( ! $home_page ) {
			$home_id = wp_insert_post(
				array(
					'post_title'  => 'Trang chủ',
					'post_name'   => 'home',
					'post_status' => 'publish',
					'post_type'   => 'page',
				)
			);
		} else {
			$home_id = $home_page->ID;
		}

		update_option( 'show_on_front', 'page' );
		update_option( 'page_on_front', $home_id );
		update_option( 'page_for_posts', 0 );

		$this->sync_primary_menu( $page_ids );

		if ( class_exists( 'Di_Restaurant_Rosa_Features' ) ) {
			Di_Restaurant_Rosa_Features::get_instance()->setup_woocommerce();
		}

		set_theme_mod( 'loading_icon', '0' );
		set_theme_mod( 'back_to_top', '0' );
		set_theme_mod( 'endis_ftr_wdgt', '0' );

		update_option( 'di_restaurant_rosa_demo_imported', 1 );
	}

	/**
	 * Sync primary and footer menus.
	 *
	 * @param array $page_ids Page IDs keyed by slug.
	 */
	public function sync_primary_menu( $page_ids = array() ) {
		if ( empty( $page_ids ) ) {
			$page_ids = array(
				'menu'         => get_page_by_path( 'menu' ) ? get_page_by_path( 'menu' )->ID : 0,
				'reservations' => get_page_by_path( 'reservations' ) ? get_page_by_path( 'reservations' )->ID : 0,
				'contact'      => get_page_by_path( 'contact' ) ? get_page_by_path( 'contact' )->ID : 0,
				'feedback'     => get_page_by_path( 'feedback' ) ? get_page_by_path( 'feedback' )->ID : 0,
			);
		}

		$menu_id = $this->get_or_create_menu( 'Rosa Primary Menu' );
		if ( ! $menu_id ) {
			return;
		}

		$this->clear_menu_items( $menu_id );

		$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

		$items = array(
			array( 'title' => 'Trang chủ', 'url' => home_url( '/' ) ),
			array( 'title' => 'Thực đơn', 'url' => ! empty( $page_ids['menu'] ) ? get_permalink( $page_ids['menu'] ) : home_url( '/menu/' ) ),
			array( 'title' => 'Cửa hàng', 'url' => $shop_url ),
			array( 'title' => 'Đặt bàn', 'url' => ! empty( $page_ids['reservations'] ) ? get_permalink( $page_ids['reservations'] ) : home_url( '/reservations/' ) ),
			array( 'title' => 'Góp ý', 'url' => ! empty( $page_ids['feedback'] ) ? get_permalink( $page_ids['feedback'] ) : home_url( '/feedback/' ) ),
			array( 'title' => 'Liên hệ', 'url' => ! empty( $page_ids['contact'] ) ? get_permalink( $page_ids['contact'] ) : home_url( '/contact/' ) ),
		);

		foreach ( $items as $item ) {
			wp_update_nav_menu_item(
				$menu_id,
				0,
				array(
					'menu-item-title'  => $item['title'],
					'menu-item-url'    => $item['url'],
					'menu-item-status' => 'publish',
				)
			);
		}

		$locations            = get_theme_mod( 'nav_menu_locations', array() );
		$locations['primary'] = $menu_id;
		set_theme_mod( 'nav_menu_locations', $locations );

		$footer_menu_id = $this->get_or_create_menu( 'Rosa Footer Menu' );
		if ( $footer_menu_id ) {
			$this->clear_menu_items( $footer_menu_id );

			$footer_items = array(
				'Bản quyền & Quyền riêng tư' => '#',
				'Liên hệ'                      => ! empty( $page_ids['contact'] ) ? get_permalink( $page_ids['contact'] ) : home_url( '/contact/' ),
			);

			foreach ( $footer_items as $title => $url ) {
				wp_update_nav_menu_item(
					$footer_menu_id,
					0,
					array(
						'menu-item-title'  => $title,
						'menu-item-url'    => $url,
						'menu-item-status' => 'publish',
					)
				);
			}

			$locations['footer'] = $footer_menu_id;
			set_theme_mod( 'nav_menu_locations', $locations );
		}
	}

	/**
	 * Get or create a nav menu by name.
	 *
	 * @param string $menu_name Menu name.
	 * @return int
	 */
	private function get_or_create_menu( $menu_name ) {
		$menu = wp_get_nav_menu_object( $menu_name );

		if ( $menu ) {
			return (int) $menu->term_id;
		}

		$menu_id = wp_create_nav_menu( $menu_name );
		return is_wp_error( $menu_id ) ? 0 : (int) $menu_id;
	}

	/**
	 * Remove all items from a menu.
	 *
	 * @param int $menu_id Menu ID.
	 */
	private function clear_menu_items( $menu_id ) {
		$items = wp_get_nav_menu_items( $menu_id );

		if ( empty( $items ) ) {
			return;
		}

		foreach ( $items as $item ) {
			wp_delete_post( $item->ID, true );
		}
	}

	/**
	 * Admin list table columns.
	 *
	 * @param array $columns Columns.
	 * @return array
	 */
	public function submission_columns( $columns ) {
		$new_columns = array();

		foreach ( $columns as $key => $label ) {
			$new_columns[ $key ] = $label;

			if ( 'title' === $key ) {
				$new_columns['rosa_type'] = __( 'Type', 'di-restaurant' );
				$new_columns['rosa_email'] = __( 'Email', 'di-restaurant' );
			}
		}

		return $new_columns;
	}

	/**
	 * Admin list table column content.
	 *
	 * @param string $column Column name.
	 * @param int    $post_id Post ID.
	 */
	public function submission_column_content( $column, $post_id ) {
		if ( 'rosa_type' === $column ) {
			echo esc_html( get_post_meta( $post_id, '_rosa_submission_type', true ) );
		}

		if ( 'rosa_email' === $column ) {
			echo esc_html( get_post_meta( $post_id, '_rosa_email', true ) );
		}
	}

	/**
	 * Verify AJAX request.
	 */
	private function verify_ajax_request() {
		check_ajax_referer( 'rosa_forms_ajax', 'rosa_ajax_nonce' );
	}

	/**
	 * Store a Rosa form submission.
	 *
	 * @param string $type Submission type.
	 * @param string $title Submission title.
	 * @param string $content Submission content.
	 * @param array  $meta Submission meta.
	 * @return int|false
	 */
	private function store_submission( $type, $title, $content, $meta = array() ) {
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'rosa_submission',
				'post_status'  => 'publish',
				'post_title'   => $title,
				'post_content' => $content,
			)
		);

		if ( is_wp_error( $post_id ) || ! $post_id ) {
			return false;
		}

		update_post_meta( $post_id, '_rosa_submission_type', sanitize_key( $type ) );

		foreach ( $meta as $key => $value ) {
			update_post_meta( $post_id, '_rosa_' . sanitize_key( $key ), sanitize_text_field( $value ) );
		}

		return $post_id;
	}

	/**
	 * Handle feedback form submission.
	 */
	public function handle_feedback_submit() {
		$this->verify_ajax_request();

		if ( empty( $_POST['rosa_feedback_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rosa_feedback_nonce'] ) ), 'rosa_feedback_submit' ) ) {
			wp_send_json_error( array( 'message' => 'Xác thực không thành công.' ) );
		}

		$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$rating  = isset( $_POST['rating'] ) ? absint( $_POST['rating'] ) : 0;
		$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

		if ( empty( $name ) || empty( $message ) || $rating < 1 || $rating > 5 ) {
			wp_send_json_error( array( 'message' => 'Vui lòng điền đầy đủ các trường bắt buộc.' ) );
		}

		$stored = $this->store_submission(
			'feedback',
			sprintf( 'Góp ý từ %s', $name ),
			$message,
			array(
				'name'   => $name,
				'email'  => $email,
				'rating' => $rating,
			)
		);

		if ( ! $stored ) {
			wp_send_json_error( array( 'message' => 'Không thể lưu góp ý. Vui lòng thử lại.' ) );
		}

		wp_send_json_success( array( 'message' => 'Cảm ơn bạn đã góp ý!' ) );
	}

	/**
	 * Handle contact form submission.
	 */
	public function handle_contact_submit() {
		$this->verify_ajax_request();

		if ( empty( $_POST['rosa_contact_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rosa_contact_nonce'] ) ), 'rosa_contact_submit' ) ) {
			wp_send_json_error( array( 'message' => 'Xác thực không thành công.' ) );
		}

		$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$subject = isset( $_POST['subject'] ) ? sanitize_text_field( wp_unslash( $_POST['subject'] ) ) : '';
		$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

		if ( empty( $name ) || empty( $email ) || empty( $message ) || ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => 'Vui lòng điền đầy đủ thông tin với email hợp lệ.' ) );
		}

		$stored = $this->store_submission(
			'contact',
			sprintf( 'Liên hệ từ %s', $name ),
			$message,
			array(
				'name'    => $name,
				'email'   => $email,
				'subject' => $subject,
			)
		);

		if ( ! $stored ) {
			wp_send_json_error( array( 'message' => 'Không thể gửi tin nhắn. Vui lòng thử lại.' ) );
		}

		wp_send_json_success( array( 'message' => 'Tin nhắn đã được gửi. Chúng tôi sẽ phản hồi sớm.' ) );
	}

	/**
	 * Handle reservation form submission.
	 */
	public function handle_reservation_submit() {
		$this->verify_ajax_request();

		if ( empty( $_POST['rosa_reservation_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rosa_reservation_nonce'] ) ), 'rosa_reservation_submit' ) ) {
			wp_send_json_error( array( 'message' => 'Xác thực không thành công.' ) );
		}

		$name   = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$email  = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$phone  = isset( $_POST['phone'] ) ? sanitize_text_field( wp_unslash( $_POST['phone'] ) ) : '';
		$guests = isset( $_POST['guests'] ) ? absint( $_POST['guests'] ) : 0;
		$date   = isset( $_POST['date'] ) ? sanitize_text_field( wp_unslash( $_POST['date'] ) ) : '';
		$time   = isset( $_POST['time'] ) ? sanitize_text_field( wp_unslash( $_POST['time'] ) ) : '';
		$notes  = isset( $_POST['notes'] ) ? sanitize_textarea_field( wp_unslash( $_POST['notes'] ) ) : '';

		if ( empty( $name ) || empty( $email ) || empty( $phone ) || empty( $date ) || empty( $time ) || $guests < 1 || ! is_email( $email ) ) {
			wp_send_json_error( array( 'message' => 'Vui lòng điền đầy đủ thông tin đặt bàn.' ) );
		}

		$content = sprintf(
			"Date: %s\nTime: %s\nGuests: %d\nPhone: %s\nNotes: %s",
			$date,
			$time,
			$guests,
			$phone,
			$notes
		);

		$stored = $this->store_submission(
			'reservation',
			sprintf( 'Đặt bàn cho %s', $name ),
			$content,
			array(
				'name'   => $name,
				'email'  => $email,
				'phone'  => $phone,
				'guests' => $guests,
				'date'   => $date,
				'time'   => $time,
				'notes'  => $notes,
			)
		);

		if ( ! $stored ) {
			wp_send_json_error( array( 'message' => 'Không thể lưu đặt bàn. Vui lòng thử lại.' ) );
		}

		wp_send_json_success( array( 'message' => 'Yêu cầu đặt bàn đã được ghi nhận. Chúng tôi sẽ xác nhận sớm.' ) );
	}

	/**
	 * Fallback navigation for Rosa header.
	 */
	public static function nav_fallback() {
		$shop_url = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/shop/' );

		$links = array(
			array( 'label' => 'Trang chủ', 'url' => home_url( '/' ) ),
			array( 'label' => 'Thực đơn', 'url' => home_url( '/menu/' ) ),
			array( 'label' => 'Cửa hàng', 'url' => $shop_url ),
			array( 'label' => 'Đặt bàn', 'url' => home_url( '/reservations/' ) ),
			array( 'label' => 'Góp ý', 'url' => home_url( '/feedback/' ) ),
			array( 'label' => 'Liên hệ', 'url' => home_url( '/contact/' ) ),
		);

		echo '<ul class="rosa-nav">';
		foreach ( $links as $link ) {
			printf(
				'<li><a href="%1$s">%2$s</a></li>',
				esc_url( $link['url'] ),
				esc_html( $link['label'] )
			);
		}
		echo '</ul>';
	}
}

Di_Restaurant_Rosa::get_instance();
