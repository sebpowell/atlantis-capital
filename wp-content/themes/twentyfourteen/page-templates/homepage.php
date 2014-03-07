<?php
/**
 * Template Name: Homepage
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 */

get_header(); ?>

<?php $background = wp_get_attachment_image_src( get_post_thumbnail_id( $page->ID ), 'full' ); ?>

<div class="l-intro" style="background-image: url('<?php echo $background[0]; ?>')">
	<div class='photo-overlay'>
		<h1 class='title'>&bull; Welcome &bull;</h1>
    	<p class='quote'><?php the_field('intro'); ?></p>
   		<a class='btn-white' href='philosophy'>Read More</a>
  	</div>
</div>
<?php
get_footer();
