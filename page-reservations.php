<?php
/**
 * Rosa Reservations page template.
 *
 * @package Di Restaurant
 */

get_header( 'rosa' );
?>

<main class="rosa-page rosa-page--reservations" id="content">
	<?php get_template_part( 'template-parts/rosa/page', 'reservations' ); ?>
</main>

<?php
get_footer( 'rosa' );
