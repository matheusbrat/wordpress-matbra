<?php get_header();

get_template_part( 'content/archive-header' );

?>
	<div class="top-banner" style="background:white;">
		<?php the_ad_placement('manual'); ?>
	</div>
	<div id="loop-container" class="loop-container">
		<?php
		if ( have_posts() ) :
			while ( have_posts() ) :
				the_post();
				ct_author_get_content_template();
			endwhile;
		endif;
		?>
	</div>
<?php

the_posts_pagination( array(
	'prev_text' => __( 'Previous', 'author' ),
    'next_text' => __( 'Next', 'author' )
) );

get_footer();