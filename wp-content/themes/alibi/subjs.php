<?php
/*
 Template Name: Subtitle JS
 */
?>
<?php get_header(); ?>
<div id="content">
	<div id="content-inner">
		<div id="main">
			<div align="center">
				<script type="text/javascript"><!--
				google_ad_client = "ca-pub-7865232871831497";
				/* 468x60, criado 26/03/10, TOPO */
				google_ad_slot = "7454183437";
				google_ad_width = 468;
				google_ad_height = 60;
				//-->
				</script>
				<script type="text/javascript"
					src="http://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
			</div>
			<?php if (have_posts()) : ?>

			<?php while (have_posts()) : the_post(); ?>

			<div class="post" id="post-<?php the_ID(); ?>">

				<div class="date">

					<span class="dateMonth"><?php the_time('M'); ?> </span> <span
						class="dateDay"><?php the_time('d'); ?> </span> <span
						class="dateYear"><?php the_time('Y'); ?> </span>
				</div>

				<h2>
					<a href="<?php the_permalink() ?>" rel="bookmark"
						title="Permanent Link to <?php the_title(); ?>"><?php the_title(); ?>
					</a>
				</h2>
				<div class="entry">
					<?php include("/home/matbrac/public_html/subwt/subwt.php"); ?>
				</div>



				<div class="postmetadata">

					<p>
					<?php the_author_posts_link(); ?>
						|
						<?php the_date('d F Y'); ?>
						|
						<?php edit_post_link('Edit',' ',''); ?>
					</p>

				</div>


				<?php comments_template(); ?>


			</div>




			<?php endwhile; ?>



			<div id="navigation">
				<div class="fleft">
				<?php next_posts_link('&laquo; Older') ?>
				</div>
				<div class="fright">
				<?php previous_posts_link('Newer &raquo;') ?>
				</div>
			</div>



			<?php else : ?>

			<div class="post">
				<div class="entry">
					<h2>Not Found</h2>
					<p>Sorry, you are looking for something that isn't here.</p>
				</div>
			</div>

			<?php endif; ?>




		</div>
		<!-- eof main -->

	</div>
</div>
<script type="text/javascript" src="http://subwt.matbra.com/subwt.js"></script>
<?php get_sidebar(); ?>

<?php get_footer(); ?>