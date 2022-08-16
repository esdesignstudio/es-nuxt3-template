<?php get_header(); ?>
	<main role="main">
		<section>
			<article id="post-404">
				<h1><?php _e( 'Page not found', 'easondesign_lang' ); ?></h1>
				<h2><a href="<?php echo home_url(); ?>"><?php _e( 'Return home?', 'easondesign_lang' ); ?></a></h2>
			</article>
		</section>
	</main>
<?php get_sidebar(); ?>
<?php get_footer(); ?>
