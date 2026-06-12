<?php
/**
 * Rosa AI chatbot widget.
 *
 * @package Di Restaurant
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<div id="rosa-chatbot" class="rosa-chatbot" aria-live="polite">
	<button type="button" class="rosa-chatbot__toggle" aria-expanded="false" aria-controls="rosa-chatbot-panel">
		<span class="rosa-chatbot__toggle-icon" aria-hidden="true">
			<svg width="22" height="22" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
				<path d="M4 5.5C4 4.67 4.67 4 5.5 4h13c.83 0 1.5.67 1.5 1.5v9c0 .83-.67 1.5-1.5 1.5H9l-4.5 3V5.5z" stroke="currentColor" stroke-width="1.5" stroke-linejoin="round"/>
			</svg>
		</span>
		<span class="rosa-chatbot__toggle-label">Tư vấn món</span>
	</button>

	<div id="rosa-chatbot-panel" class="rosa-chatbot__panel" hidden>
		<header class="rosa-chatbot__header">
			<div>
				<p class="rosa-chatbot__header-sub">Hanoi Home Taste</p>
				<h2 class="rosa-chatbot__header-title">Trợ lý ẩm thực</h2>
			</div>
			<button type="button" class="rosa-chatbot__close" aria-label="Đóng chat">&times;</button>
		</header>

		<div class="rosa-chatbot__messages" id="rosa-chatbot-messages" role="log" aria-relevant="additions"></div>

		<div class="rosa-chatbot__suggestions" id="rosa-chatbot-suggestions"></div>

		<form class="rosa-chatbot__form" id="rosa-chatbot-form">
			<label class="screen-reader-text" for="rosa-chatbot-input">Nhập câu hỏi</label>
			<input type="text" id="rosa-chatbot-input" class="rosa-chatbot__input" maxlength="500" autocomplete="off">
			<button type="submit" class="rosa-chatbot__send">Gửi</button>
		</form>
	</div>
</div>
