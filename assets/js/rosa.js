(function ($) {
	'use strict';

	var $header = $('#rosa-header');
	var $toggle = $('.rosa-header__toggle');
	var $navActions = $('.rosa-header__actions');
	var lastScroll = 0;

	function updateHeader() {
		var scrollTop = $(window).scrollTop();

		if (scrollTop > 80) {
			$header.addClass('is-scrolled');
		} else {
			$header.removeClass('is-scrolled');
		}

		if (scrollTop > lastScroll && scrollTop > 240) {
			$header.addClass('is-hidden');
		} else {
			$header.removeClass('is-hidden');
		}

		lastScroll = scrollTop;
	}

	function initParallax() {
		if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
			return;
		}

		$('.rosa-section[data-parallax-image]').each(function () {
			var $section = $(this);
			var image = $section.data('parallax-image');

			if (image) {
				$section.css('background-image', 'url(' + image + ')');
			}
		});
	}

	function initScrollAnimations() {
		if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
			$('.rosa-animate').addClass('is-visible');
			return;
		}

		if (!('IntersectionObserver' in window)) {
			$('.rosa-animate').addClass('is-visible');
			return;
		}

		var observer = new IntersectionObserver(function (entries) {
			entries.forEach(function (entry) {
				if (entry.isIntersecting) {
					entry.target.classList.add('is-visible');
					observer.unobserve(entry.target);
				}
			});
		}, {
			threshold: 0.15,
			rootMargin: '0px 0px -40px 0px'
		});

		document.querySelectorAll('.rosa-animate').forEach(function (element) {
			observer.observe(element);
		});
	}

	function initSmoothScroll() {
		$('.rosa-nav a[href*="#"], .rosa-footer__links a[href*="#"]').on('click', function (event) {
			var href = $(this).attr('href');
			var hashIndex = href.indexOf('#');

			if (hashIndex === -1) {
				return;
			}

			var target = href.substring(hashIndex);

			if (target.length < 2 || !$(target).length) {
				return;
			}

			event.preventDefault();
			$('html, body').animate({
				scrollTop: $(target).offset().top - 70
			}, 700);

			$navActions.removeClass('is-open');
			$toggle.removeClass('is-active').attr('aria-expanded', 'false');
		});
	}

	function showFormMessage($el, message, isError) {
		$el.text(message).removeClass('is-error is-success').addClass(isError ? 'is-error' : 'is-success');
	}

	function submitRosaForm($form, $message) {
		var $button = $form.find('button[type="submit"]');
		var formData = new FormData($form[0]);
		formData.append('rosa_ajax_nonce', window.diRosaForms ? window.diRosaForms.nonce : '');

		$button.prop('disabled', true);
		$message.removeClass('is-error is-success').text('');

		$.ajax({
			url: window.diRosaForms ? window.diRosaForms.ajaxUrl : '/wp-admin/admin-ajax.php',
			type: 'POST',
			data: formData,
			processData: false,
			contentType: false
		}).done(function (response) {
			if (response.success) {
				showFormMessage($message, response.data.message, false);
				$form[0].reset();
			} else {
				showFormMessage($message, response.data && response.data.message ? response.data.message : 'Đã xảy ra lỗi.', true);
			}
		}).fail(function () {
			showFormMessage($message, 'Không thể gửi. Vui lòng thử lại.', true);
		}).always(function () {
			$button.prop('disabled', false);
		});
	}

	$toggle.on('click', function () {
		var isOpen = $navActions.toggleClass('is-open').hasClass('is-open');
		$toggle.toggleClass('is-active', isOpen).attr('aria-expanded', isOpen ? 'true' : 'false');
	});

	$('#rosa-feedback-form, #rosa-contact-form, #rosa-reservation-form').on('submit', function (event) {
		event.preventDefault();
		var $form = $(this);
		var messageId = '#' + $form.attr('id').replace('-form', '-message');
		submitRosaForm($form, $(messageId));
	});

	$(window).on('scroll', updateHeader);
	$(window).on('resize', updateHeader);

	$(function () {
		updateHeader();
		initParallax();
		initScrollAnimations();
		initSmoothScroll();

		var $dateField = $('#reservation-date');
		if ($dateField.length) {
			var today = new Date().toISOString().split('T')[0];
			$dateField.attr('min', today);
		}
	});
}(jQuery));
