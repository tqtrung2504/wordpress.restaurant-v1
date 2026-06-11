<?php
/**
 * Rosa Menu page template.
 *
 * @package Di Restaurant
 */

get_header( 'rosa' );
?>

<main class="rosa-page rosa-page--menu" id="content">
	<?php get_template_part( 'template-parts/rosa/page', 'menu' ); ?>
</main>

<?php
get_footer( 'rosa' );
