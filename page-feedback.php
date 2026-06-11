<?php
/**
 * Rosa Feedback page template.
 *
 * @package Di Restaurant
 */

get_header( 'rosa' );
?>

<main class="rosa-page rosa-page--feedback" id="content">
	<?php get_template_part( 'template-parts/rosa/page', 'feedback' ); ?>
</main>

<?php
get_footer( 'rosa' );
