<?php
/*
 * Template Name: Page Builder
 */
?>

<?php get_header(); ?>

<div id="site-content">

	<?php while ( have_posts() ) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; ?>

</div>

<?php get_footer();
