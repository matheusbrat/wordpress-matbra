<?php get_header();

get_template_part( 'content/archive-header' );

?>
	<div class="top-banner entry" style="background:white;">
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- Wordpress Matbra.com -->
		<ins class="adsbygoogle"
		     style="display:block"
		     data-ad-client="ca-pub-7865232871831497"
		     data-ad-slot="1027287528"
		     data-ad-format="auto"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>
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