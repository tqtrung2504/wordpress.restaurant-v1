<?php
/**
 * Rosa AI chatbot powered by Google Gemini.
 *
 * @package Di Restaurant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class Di_Restaurant_Rosa_Chatbot.
 */
class Di_Restaurant_Rosa_Chatbot {

	const OPTION_API_KEY = 'di_restaurant_gemini_api_key';
	const OPTION_MODEL   = 'di_restaurant_gemini_model';
	const OPTION_ENABLED = 'di_restaurant_chatbot_enabled';

	/**
	 * Instance.
	 *
	 * @var Di_Restaurant_Rosa_Chatbot
	 */
	public static $instance;

	/**
	 * Get instance.
	 *
	 * @return Di_Restaurant_Rosa_Chatbot
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
		add_action( 'admin_menu', array( $this, 'register_admin_page' ) );
		add_action( 'admin_init', array( $this, 'register_settings' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ), 25 );
		add_action( 'wp_footer', array( $this, 'render_widget' ), 5 );

		add_action( 'wp_ajax_rosa_chatbot_message', array( $this, 'handle_message' ) );
		add_action( 'wp_ajax_nopriv_rosa_chatbot_message', array( $this, 'handle_message' ) );
	}

	/**
	 * Whether chatbot is enabled and configured.
	 *
	 * @return bool
	 */
	public static function is_active() {
		if ( '1' !== get_option( self::OPTION_ENABLED, '1' ) ) {
			return false;
		}

		return (bool) self::get_api_key();
	}

	/**
	 * Get API key.
	 *
	 * @return string
	 */
	public static function get_api_key() {
		$key = get_option( self::OPTION_API_KEY, '' );

		if ( ! empty( $key ) ) {
			return $key;
		}

		if ( defined( 'DI_RESTAURANT_GEMINI_API_KEY' ) && DI_RESTAURANT_GEMINI_API_KEY ) {
			return DI_RESTAURANT_GEMINI_API_KEY;
		}

		return '';
	}

	/**
	 * Get Gemini model name.
	 *
	 * @return string
	 */
	public static function get_model() {
		$model = get_option( self::OPTION_MODEL, 'gemini-2.5-flash-lite' );
		return $model ? $model : 'gemini-2.5-flash-lite';
	}

	/**
	 * Models to try when primary model fails (quota, unavailable).
	 *
	 * @return array
	 */
	private function get_models_to_try() {
		$primary   = self::get_model();
		$fallbacks = array( 'gemini-2.5-flash-lite', 'gemini-1.5-flash', 'gemini-2.5-flash' );

		return array_values( array_unique( array_merge( array( $primary ), $fallbacks ) ) );
	}

	/**
	 * Register admin page.
	 */
	public function register_admin_page() {
		add_theme_page(
			'Chatbot AI',
			'Chatbot AI',
			'manage_options',
			'di-restaurant-chatbot',
			array( $this, 'render_admin_page' )
		);
	}

	/**
	 * Register settings.
	 */
	public function register_settings() {
		register_setting(
			'di_restaurant_chatbot',
			self::OPTION_API_KEY,
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		register_setting(
			'di_restaurant_chatbot',
			self::OPTION_MODEL,
			array(
				'type'              => 'string',
				'sanitize_callback' => 'sanitize_text_field',
			)
		);
		register_setting(
			'di_restaurant_chatbot',
			self::OPTION_ENABLED,
			array(
				'type'              => 'string',
				'sanitize_callback' => function ( $value ) {
					return '1' === $value ? '1' : '0';
				},
			)
		);
	}

	/**
	 * Render admin settings page.
	 */
	public function render_admin_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		?>
		<div class="wrap">
			<h1>Chatbot AI — Gemini</h1>
			<p>Cấu hình chatbot tư vấn món ăn cho <strong>Hanoi Home Taste</strong>. Lấy API key tại
				<a href="https://aistudio.google.com/apikey" target="_blank" rel="noopener noreferrer">Google AI Studio</a>.
			</p>
			<form method="post" action="options.php">
				<?php settings_fields( 'di_restaurant_chatbot' ); ?>
				<table class="form-table" role="presentation">
					<tr>
						<th scope="row"><label for="di_restaurant_chatbot_enabled">Bật chatbot</label></th>
						<td>
							<label>
								<input type="checkbox" id="di_restaurant_chatbot_enabled" name="<?php echo esc_attr( self::OPTION_ENABLED ); ?>" value="1" <?php checked( get_option( self::OPTION_ENABLED, '1' ), '1' ); ?>>
								Hiển thị chatbot trên trang nhà hàng
							</label>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="di_restaurant_gemini_api_key">Gemini API Key</label></th>
						<td>
							<input type="password" class="regular-text" id="di_restaurant_gemini_api_key" name="<?php echo esc_attr( self::OPTION_API_KEY ); ?>" value="<?php echo esc_attr( get_option( self::OPTION_API_KEY, '' ) ); ?>" autocomplete="off">
							<p class="description">Hoặc định nghĩa hằng số <code>DI_RESTAURANT_GEMINI_API_KEY</code> trong <code>wp-config.php</code>.</p>
						</td>
					</tr>
					<tr>
						<th scope="row"><label for="di_restaurant_gemini_model">Model</label></th>
						<td>
							<input type="text" class="regular-text" id="di_restaurant_gemini_model" name="<?php echo esc_attr( self::OPTION_MODEL ); ?>" value="<?php echo esc_attr( self::get_model() ); ?>">
							<p class="description">Khuyến nghị free tier: <code>gemini-2.5-flash-lite</code> hoặc <code>gemini-1.5-flash</code>. Tránh <code>gemini-2.0-flash</code> nếu báo hết quota.</p>
						</td>
					</tr>
				</table>
				<?php submit_button( 'Lưu cài đặt' ); ?>
			</form>
		</div>
		<?php
	}

	/**
	 * Enqueue chatbot assets.
	 */
	public function enqueue_assets() {
		if ( ! class_exists( 'Di_Restaurant_Rosa' ) || ! Di_Restaurant_Rosa::is_rosa_page() ) {
			return;
		}

		if ( '1' !== get_option( self::OPTION_ENABLED, '1' ) ) {
			return;
		}

		wp_enqueue_style(
			'di-restaurant-rosa-chatbot',
			get_template_directory_uri() . '/assets/css/rosa-chatbot.css',
			array( 'di-restaurant-rosa' ),
			DI_RESTAURANT_VERSION,
			'all'
		);

		wp_enqueue_script(
			'di-restaurant-rosa-chatbot',
			get_template_directory_uri() . '/assets/js/rosa-chatbot.js',
			array( 'jquery' ),
			DI_RESTAURANT_VERSION,
			true
		);

		wp_localize_script(
			'di-restaurant-rosa-chatbot',
			'diRosaChatbot',
			array(
				'ajaxUrl'  => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'rosa_chatbot_ajax' ),
				'configured' => self::is_active(),
				'welcome'  => 'Xin chào! Tôi là trợ lý ẩm thực của Hanoi Home Taste. Bạn muốn tìm hiểu món nào hôm nay?',
				'suggestions' => array(
					'Gợi ý món khai vị',
					'Phở Bò Hà Nội có gì đặc biệt?',
					'Món nào phù hợp cho 2 người?',
					'Giá Bún Chả bao nhiêu?',
				),
				'strings'  => array(
					'placeholder'   => 'Hỏi về món ăn, giá, đặt bàn...',
					'send'          => 'Gửi',
					'close'         => 'Đóng',
					'title'         => 'Trợ lý ẩm thực',
					'toggle'        => 'Mở chat tư vấn món',
					'typing'        => 'Đang trả lời...',
					'notConfigured' => 'Chatbot chưa được cấu hình. Quản trị viên vui lòng thêm Gemini API key trong Giao diện → Chatbot AI.',
					'error'         => 'Không thể kết nối. Vui lòng thử lại.',
				),
			)
		);
	}

	/**
	 * Render chatbot markup.
	 */
	public function render_widget() {
		if ( ! class_exists( 'Di_Restaurant_Rosa' ) || ! Di_Restaurant_Rosa::is_rosa_page() ) {
			return;
		}

		if ( '1' !== get_option( self::OPTION_ENABLED, '1' ) ) {
			return;
		}

		get_template_part( 'template-parts/rosa/chatbot' );
	}

	/**
	 * Build system instruction for Gemini.
	 *
	 * @return string
	 */
	private function get_system_prompt() {
		$menu_context = function_exists( 'di_restaurant_rosa_get_menu_prompt_context' )
			? di_restaurant_rosa_get_menu_prompt_context()
			: '';

		return implode(
			"\n",
			array(
				'Bạn là trợ lý ẩm thực thân thiện của nhà hàng Việt Nam "Hanoi Home Taste".',
				'Nhiệm vụ: giới thiệu món ăn, mô tả hương vị, gợi ý món phù hợp và trả lời về giá, giờ mở cửa, đặt bàn.',
				'Luôn trả lời bằng tiếng Việt, ngắn gọn (2–5 câu), ấm áp và dễ hiểu.',
				'Chỉ tư vấn dựa trên thực đơn và thông tin nhà hàng bên dưới. Nếu khách hỏi ngoài phạm vi, lịch sự hướng về món ăn hoặc liên hệ nhà hàng.',
				'Không bịa thêm món không có trong thực đơn.',
				'',
				$menu_context,
			)
		);
	}

	/**
	 * Handle chat message AJAX.
	 */
	public function handle_message() {
		if ( empty( $_POST['rosa_chatbot_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['rosa_chatbot_nonce'] ) ), 'rosa_chatbot_ajax' ) ) {
			wp_send_json_error( array( 'message' => 'Xác thực không thành công.' ) );
		}

		$message = isset( $_POST['message'] ) ? sanitize_text_field( wp_unslash( $_POST['message'] ) ) : '';

		if ( empty( $message ) ) {
			wp_send_json_error( array( 'message' => 'Vui lòng nhập câu hỏi.' ) );
		}

		if ( mb_strlen( $message ) > 500 ) {
			wp_send_json_error( array( 'message' => 'Câu hỏi quá dài (tối đa 500 ký tự).' ) );
		}

		$api_key = self::get_api_key();
		if ( empty( $api_key ) ) {
			wp_send_json_error(
				array(
					'message' => 'Chatbot chưa được cấu hình API key. Vui lòng liên hệ quản trị viên.',
				)
			);
		}

		$history = array();
		if ( ! empty( $_POST['history'] ) ) {
			$raw_history = json_decode( wp_unslash( $_POST['history'] ), true );
			if ( is_array( $raw_history ) ) {
				$history = $this->sanitize_history( $raw_history );
			}
		}

		$reply = $this->call_gemini_with_fallback( $api_key, $message, $history );

		if ( is_wp_error( $reply ) ) {
			$local = $this->local_fallback_reply( $message );
			if ( $local ) {
				wp_send_json_success(
					array(
						'reply'    => $local,
						'fallback' => true,
					)
				);
			}
			wp_send_json_error( array( 'message' => $this->friendly_error_message( $reply ) ) );
		}

		wp_send_json_success( array( 'reply' => $reply ) );
	}

	/**
	 * User-friendly error when Gemini quota is exceeded.
	 *
	 * @param WP_Error $error Error object.
	 * @return string
	 */
	private function friendly_error_message( $error ) {
		$msg = $error->get_error_message();
		if ( false !== stripos( $msg, 'quota' ) || false !== stripos( $msg, 'limit' ) ) {
			return 'Hệ thống AI tạm hết lượt miễn phí. Vui lòng thử lại sau vài phút hoặc đổi model sang gemini-2.5-flash-lite trong Giao diện → Chatbot AI.';
		}
		return $msg;
	}

	/**
	 * Try multiple Gemini models before giving up.
	 *
	 * @param string $api_key API key.
	 * @param string $message User message.
	 * @param array  $history Chat history.
	 * @return string|WP_Error
	 */
	private function call_gemini_with_fallback( $api_key, $message, $history = array() ) {
		$last_error = null;

		foreach ( $this->get_models_to_try() as $model ) {
			$reply = $this->call_gemini( $api_key, $message, $history, $model );
			if ( ! is_wp_error( $reply ) ) {
				return $reply;
			}
			$last_error = $reply;
			$err_msg      = $reply->get_error_message();
			if ( false === stripos( $err_msg, 'quota' ) && false === stripos( $err_msg, 'limit' ) && false === stripos( $err_msg, 'not found' ) ) {
				return $reply;
			}
		}

		return $last_error ? $last_error : new WP_Error( 'gemini_api', 'Gemini API trả về lỗi.' );
	}

	/**
	 * Rule-based reply from menu data when Gemini is unavailable.
	 *
	 * @param string $message User message.
	 * @return string
	 */
	private function local_fallback_reply( $message ) {
		if ( ! function_exists( 'di_restaurant_rosa_get_vietnamese_menu' ) ) {
			return '';
		}

		$text     = mb_strtolower( $message, 'UTF-8' );
		$sections = di_restaurant_rosa_get_vietnamese_menu();

		if ( $this->text_has_any( $text, array( 'đặt bàn', 'dat ban', 'reservation', 'book' ) ) ) {
			return 'Bạn có thể đặt bàn tại ' . home_url( '/reservations/' ) . '. Nhà hàng mở cửa 10:00–22:00 hàng ngày. Gọi 0901 234 567 nếu cần hỗ trợ nhanh.';
		}

		if ( $this->text_has_any( $text, array( 'giờ mở', 'mở cửa', 'gio mo', 'hours' ) ) ) {
			return 'Hanoi Home Taste mở cửa từ 10:00 đến 22:00 mỗi ngày, kể cả cuối tuần.';
		}

		if ( $this->text_has_any( $text, array( 'liên hệ', 'địa chỉ', 'lien he', 'contact', 'ở đâu' ) ) ) {
			return 'Địa chỉ: 123 Đường Ẩm Thực, Quận 1, TP. Hồ Chí Minh. Điện thoại: 0901 234 567. Trang liên hệ: ' . home_url( '/contact/' );
		}

		foreach ( $sections as $section ) {
			$slug = mb_strtolower( $section['title'], 'UTF-8' );
			if ( false !== mb_strpos( $text, $slug ) ) {
				return $this->format_section_reply( $section['title'], $section['items'] );
			}
		}

		if ( $this->text_has_any( $text, array( 'khai vị', 'khai vi', 'appetizer' ) ) ) {
			return $this->format_section_reply( 'Khai vị', $sections[0]['items'] );
		}

		if ( $this->text_has_any( $text, array( 'món chính', 'mon chinh', 'main' ) ) ) {
			return $this->format_section_reply( 'Món chính', $sections[1]['items'] );
		}

		if ( $this->text_has_any( $text, array( 'tráng miệng', 'trang mieng', 'dessert', 'chè', 'che' ) ) ) {
			return $this->format_section_reply( 'Tráng miệng', $sections[2]['items'] );
		}

		if ( $this->text_has_any( $text, array( 'đồ uống', 'do uong', 'uống', 'drink', 'cà phê', 'ca phe' ) ) ) {
			return $this->format_section_reply( 'Đồ uống', $sections[3]['items'] );
		}

		foreach ( $sections as $section ) {
			foreach ( $section['items'] as $item ) {
				$name = mb_strtolower( $item['name'], 'UTF-8' );
				if ( false !== mb_strpos( $text, $name ) || $this->text_has_any( $text, $this->name_keywords( $item['name'] ) ) ) {
					$price = di_restaurant_rosa_format_vnd( $item['price'] );
					return $item['name'] . ' — ' . $price . '. ' . $item['description'];
				}
			}
		}

		if ( $this->text_has_any( $text, array( 'gợi ý', 'goi y', 'nên ăn', 'nen an', 'recommend', '2 người', 'hai người' ) ) ) {
			return 'Gợi ý cho bạn: Gỏi Cuốn Tôm Thịt (75.000 ₫) làm khai vị, Phở Bò Hà Nội (70.000 ₫) hoặc Bún Chả Hà Nội (68.000 ₫) làm món chính, kết thúc bằng Chè Khúc Bạch (30.000 ₫). Xem đầy đủ tại ' . home_url( '/menu/' );
		}

		if ( $this->text_has_any( $text, array( 'giá', 'gia', 'bao nhiêu', 'price', 'cost' ) ) ) {
			$lines = array( 'Bảng giá một số món nổi bật:' );
			foreach ( array( $sections[1]['items'][0], $sections[1]['items'][1], $sections[0]['items'][0] ) as $item ) {
				$lines[] = '• ' . $item['name'] . ': ' . di_restaurant_rosa_format_vnd( $item['price'] );
			}
			$lines[] = 'Xem thực đơn đầy đủ: ' . home_url( '/menu/' );
			return implode( "\n", $lines );
		}

		if ( $this->text_has_any( $text, array( 'thực đơn', 'thuc don', 'menu', 'món', 'mon' ) ) ) {
			return 'Thực đơn gồm Khai vị, Món chính, Tráng miệng và Đồ uống — đặc sản Việt như phở, cơm tấm, bún chả, gỏi cuốn. Xem chi tiết: ' . home_url( '/menu/' );
		}

		return 'Chào bạn! Tôi có thể giới thiệu món ăn, giá cả và hướng dẫn đặt bàn. Hãy hỏi ví dụ: "Gợi ý món khai vị", "Phở bò giá bao nhiêu?" hoặc "Đặt bàn thế nào?".';
	}

	/**
	 * Check if text contains any keyword.
	 *
	 * @param string $text     Haystack.
	 * @param array  $keywords Needles.
	 * @return bool
	 */
	private function text_has_any( $text, $keywords ) {
		foreach ( $keywords as $keyword ) {
			if ( false !== mb_strpos( $text, mb_strtolower( $keyword, 'UTF-8' ) ) ) {
				return true;
			}
		}
		return false;
	}

	/**
	 * Short keywords for dish name matching.
	 *
	 * @param string $name Dish name.
	 * @return array
	 */
	private function name_keywords( $name ) {
		$parts = preg_split( '/\s+/', mb_strtolower( $name, 'UTF-8' ) );
		return array_filter( $parts, function ( $part ) {
			return mb_strlen( $part, 'UTF-8' ) >= 3;
		} );
	}

	/**
	 * Format a menu section as chat reply.
	 *
	 * @param string $title Section title.
	 * @param array  $items Menu items.
	 * @return string
	 */
	private function format_section_reply( $title, $items ) {
		$lines = array( $title . ' tại Hanoi Home Taste:' );
		foreach ( $items as $item ) {
			$lines[] = '• ' . $item['name'] . ' — ' . di_restaurant_rosa_format_vnd( $item['price'] ) . ': ' . $item['description'];
		}
		return implode( "\n", $lines );
	}

	/**
	 * Sanitize conversation history from client.
	 *
	 * @param array $history Raw history.
	 * @return array
	 */
	private function sanitize_history( $history ) {
		$clean = array();

		foreach ( array_slice( $history, -8 ) as $item ) {
			if ( empty( $item['role'] ) || empty( $item['text'] ) ) {
				continue;
			}

			$role = 'model' === $item['role'] ? 'model' : 'user';
			$text = sanitize_text_field( $item['text'] );

			if ( '' === $text ) {
				continue;
			}

			$clean[] = array(
				'role' => $role,
				'text' => mb_substr( $text, 0, 1000 ),
			);
		}

		return $clean;
	}

	/**
	 * Call Google Gemini generateContent API.
	 *
	 * @param string $api_key API key.
	 * @param string $message User message.
	 * @param array  $history Prior messages.
	 * @return string|WP_Error
	 */
	private function call_gemini( $api_key, $message, $history = array(), $model = '' ) {
		if ( empty( $model ) ) {
			$model = self::get_model();
		}
		$url   = sprintf(
			'https://generativelanguage.googleapis.com/v1beta/models/%s:generateContent?key=%s',
			rawurlencode( $model ),
			rawurlencode( $api_key )
		);

		$contents = array();
		foreach ( $history as $item ) {
			$contents[] = array(
				'role'  => $item['role'],
				'parts' => array(
					array( 'text' => $item['text'] ),
				),
			);
		}

		$contents[] = array(
			'role'  => 'user',
			'parts' => array(
				array( 'text' => $message ),
			),
		);

		$body = array(
			'systemInstruction' => array(
				'parts' => array(
					array( 'text' => $this->get_system_prompt() ),
				),
			),
			'contents'          => $contents,
			'generationConfig'  => array(
				'temperature'     => 0.7,
				'maxOutputTokens' => 600,
			),
		);

		$response = wp_remote_post(
			$url,
			array(
				'headers' => array( 'Content-Type' => 'application/json' ),
				'body'    => wp_json_encode( $body ),
				'timeout' => 30,
			)
		);

		if ( is_wp_error( $response ) ) {
			return new WP_Error( 'gemini_network', 'Không thể kết nối tới Gemini API.' );
		}

		$code = wp_remote_retrieve_response_code( $response );
		$data = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( $code < 200 || $code >= 300 ) {
			$error_text = 'Gemini API trả về lỗi.';
			if ( ! empty( $data['error']['message'] ) ) {
				$error_text = sanitize_text_field( $data['error']['message'] );
			}
			return new WP_Error( 'gemini_api', $error_text );
		}

		if ( empty( $data['candidates'][0]['content']['parts'][0]['text'] ) ) {
			return new WP_Error( 'gemini_empty', 'Không nhận được phản hồi từ AI.' );
		}

		return trim( $data['candidates'][0]['content']['parts'][0]['text'] );
	}
}

Di_Restaurant_Rosa_Chatbot::get_instance();
