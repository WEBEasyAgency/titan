<?php get_header(); ?>

<main class="main-page">
	<section class="top-block">
		<div class="container">
			<?php
			if ( have_posts() ) :
				while ( have_posts() ) :
					the_post();
					the_content();
				endwhile;
			endif;
			?>
		</div>
	</section>
</main>

<?php get_footer(); ?>
