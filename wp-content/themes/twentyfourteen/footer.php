<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */
?>

</div><!-- #main -->

	<footer>
	 	<div class='container-narrow'>
	 		<?php get_sidebar( 'footer' ); ?>
	  	</div>
	</footer>
</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>