<?php
/**
 * Template Name: Contact
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

	<div class="hero hero-contact">
		 <iframe width="100%" height="500" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.com/maps/ms?msa=0&amp;msid=207868692535093375127.0004f32e5f55fc3e97025&amp;ie=UTF8&amp;ll=51.506085,-0.136208&amp;spn=0,0&amp;t=m&amp;output=embed"></iframe>
	</div>
	<div class="page-content">
		<div class="container-narrow">
			<?php
				while ( have_posts() ) : the_post();
					get_template_part( 'content-page');
				endwhile;
			?>
		</div><!-- #content -->
<?php
get_footer();
