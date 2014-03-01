<?php
/**
 * Template Name: Full Width
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<?php $background = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), 'full' ); ?>

	<div class="hero" style="background-image: url('<?php echo $background[0]; ?>')">
		<div class='container-site'>
    		<p class='quote'><?php the_field('quote'); ?></p>
   			<p class='quote-author'><?php the_field('author'); ?></p>
  		</div>
	</div>
	<div class="page-content">
		<div class="container-narrow" role="main">
			<?php
				while ( have_posts() ) : the_post();
					get_template_part( 'content-page');
				endwhile;
			?>
</div><!-- #main-content -->

<?php
get_footer();
