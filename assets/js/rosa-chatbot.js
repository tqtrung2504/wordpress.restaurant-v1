(function ($) {
	'use strict';

	var config = window.diRosaChatbot || {};
	var $root = $('#rosa-chatbot');
	if (!$root.length) {
		return;
	}

	var $toggle = $root.find('.rosa-chatbot__toggle');
	var $panel = $root.find('.rosa-chatbot__panel');
	var $messages = $('#rosa-chatbot-messages');
	var $suggestions = $('#rosa-chatbot-suggestions');
	var $form = $('#rosa-chatbot-form');
	var $input = $('#rosa-chatbot-input');
	var history = [];
	var isSending = false;

	function strings(key) {
		return (config.strings && config.strings[key]) || '';
	}

	function appendMessage(role, text) {
		var $bubble = $('<div>', {
			class: 'rosa-chatbot__message rosa-chatbot__message--' + role
		});
		$bubble.append($('<p>').text(text));
		$messages.append($bubble);
		$messages.scrollTop($messages[0].scrollHeight);
	}

	function setTyping(show) {
		$root.find('.rosa-chatbot__typing').remove();
		if (show) {
			$messages.append(
				$('<div>', { class: 'rosa-chatbot__message rosa-chatbot__message--bot rosa-chatbot__typing' })
					.append($('<p>').text(strings('typing') || 'Đang trả lời...'))
			);
			$messages.scrollTop($messages[0].scrollHeight);
		}
	}

	function renderSuggestions() {
		$suggestions.empty();
		var items = config.suggestions || [];
		items.forEach(function (text) {
			$('<button>', {
				type: 'button',
				class: 'rosa-chatbot__chip',
				text: text
			}).on('click', function () {
				sendMessage(text);
			}).appendTo($suggestions);
		});
	}

	function openPanel() {
		$panel.removeAttr('hidden').prop('hidden', false);
		$toggle.attr('aria-expanded', 'true');
		$root.addClass('is-open');
		$input.trigger('focus');
	}

	function closePanel() {
		$panel.attr('hidden', 'hidden').prop('hidden', true);
		$toggle.attr('aria-expanded', 'false');
		$root.removeClass('is-open');
	}

	function sendMessage(text) {
		var message = (text || $input.val()).trim();
		if (!message || isSending) {
			return;
		}

		if (!config.configured) {
			appendMessage('bot', strings('notConfigured'));
			return;
		}

		appendMessage('user', message);
		$input.val('');
		$suggestions.hide();
		isSending = true;
		setTyping(true);

		$.ajax({
			url: config.ajaxUrl || '/wp-admin/admin-ajax.php',
			type: 'POST',
			data: {
				action: 'rosa_chatbot_message',
				rosa_chatbot_nonce: config.nonce,
				message: message,
				history: JSON.stringify(history)
			}
		}).done(function (response) {
			setTyping(false);
			if (response.success && response.data && response.data.reply) {
				appendMessage('bot', response.data.reply);
				history.push({ role: 'user', text: message });
				history.push({ role: 'model', text: response.data.reply });
				if (history.length > 10) {
					history = history.slice(-10);
				}
			} else {
				var err = (response.data && response.data.message) ? response.data.message : strings('error');
				appendMessage('bot', err);
			}
		}).fail(function () {
			setTyping(false);
			appendMessage('bot', strings('error'));
		}).always(function () {
			isSending = false;
		});
	}

	$toggle.on('click', function () {
		if ($root.hasClass('is-open')) {
			closePanel();
		} else {
			openPanel();
		}
	});

	$root.find('.rosa-chatbot__close').on('click', function (event) {
		event.preventDefault();
		event.stopPropagation();
		closePanel();
	});

	$form.on('submit', function (event) {
		event.preventDefault();
		sendMessage();
	});

	$(function () {
		if (config.welcome) {
			appendMessage('bot', config.welcome);
		}
		renderSuggestions();
		$input.attr('placeholder', strings('placeholder'));
		$form.find('.rosa-chatbot__send').text(strings('send'));
	});
}(jQuery));
