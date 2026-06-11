
<footer class="rosa-footer">
	<div class="rosa-footer__inner">
		<div class="rosa-footer__counter">
			<span class="rosa-footer__counter-icon" aria-hidden="true">&#9679;</span>
			<?php
			printf(
				'Lượt truy cập: %s',
				esc_html( Di_Restaurant_Rosa_Features::get_visitor_count() )
			);
			?>
		</div>

		<div class="rosa-footer__credit">
			<?php
			printf(
				'Phát triển bởi %1$s | %2$s',
				'<a href="' . esc_url( __( 'https://wordpress.org/', 'di-restaurant' ) ) . '">WordPress</a>',
				'<a href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>'
			);
			?>
		</div>

		<?php if ( has_nav_menu( 'footer' ) ) : ?>
			<nav class="rosa-footer__nav" aria-label="Điều hướng chân trang">
				<?php
				wp_nav_menu(
					array(
						'theme_location' => 'footer',
						'container'      => false,
						'menu_class'     => 'rosa-footer__links',
						'depth'          => 1,
						'fallback_cb'    => false,
					)
				);
				?>
			</nav>
		<?php else : ?>
			<nav class="rosa-footer__nav" aria-label="Điều hướng chân trang">
				<ul class="rosa-footer__links">
					<li><a href="#">Bản quyền &amp; Quyền riêng tư</a></li>
					<li><a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">Liên hệ</a></li>
				</ul>
			</nav>
		<?php endif; ?>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
