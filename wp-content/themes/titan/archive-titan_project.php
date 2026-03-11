<?php
/**
 * Archive Template: Проекты (titan_project)
 */
get_header();

$projects_query = new WP_Query( array(
	'post_type'      => 'titan_project',
	'posts_per_page' => 3,
	'paged'          => 1,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
) );

$has_projects = $projects_query->have_posts();
$has_more     = $has_projects && $projects_query->max_num_pages > 1;
?>

<main class="inner-page projects-page">
	<?php if ( $has_projects ) : ?>
		<section class="projects">
			<div class="container">
				<div class="title">
					<h1>Наши проекты</h1>
				</div>
				<div class="projects-list">
					<?php
					while ( $projects_query->have_posts() ) {
						$projects_query->the_post();
						get_template_part( 'template-parts/project', 'card' );
					}
					wp_reset_postdata();
					?>
				</div>
				<?php if ( $has_more ) : ?>
					<div class="btn-block">
						<a href="#" class="more-btn">Показать ещё</a>
					</div>
				<?php endif; ?>
			</div>
		</section>
	<?php else : ?>
		<section class="projects developing">
			<div class="container">
				<div class="title">
					<h1>Наши проекты</h1>
				</div>
				<div class="develop-block">
					<div class="text">Страница находится<br> в разработке</div>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<section class="contacts-form">
		<div class="container">
			<div class="grid">
				<div class="title">
					<h3>Свяжитесь с нами</h3>
				</div>
				<div class="form-block">
					<?php echo do_shortcode( '[contact-form-7 title="Свяжитесь с нами"]' ); ?>
				</div>
			</div>
		</div>
		<div class="file-error">
			<div class="name"></div>
			<div class="error-text">Файл слишком большой</div>
		</div>
	</section>
</main>

<?php if ( $has_projects && $has_more ) : ?>
<script>
jQuery(function($){
	var page = 1;
	$('.projects .more-btn').on('click', function(e){
		e.preventDefault();
		var $btn = $(this);
		if ($btn.hasClass('loading')) return;
		$btn.addClass('loading');
		page++;
		$.post(titan_wc.ajax_url, {
			action: 'titan_load_more_projects',
			nonce: titan_wc.nonce,
			page: page
		}, function(res){
			$btn.removeClass('loading');
			if (res.success && res.data.html) {
				$('.projects-list').append(res.data.html);
				if (!res.data.has_more) {
					$btn.closest('.btn-block').hide();
				}
			}
		}).fail(function(){
			$btn.removeClass('loading');
			page--;
		});
	});
});
</script>
<?php endif; ?>

<?php get_template_part( 'template-parts/cf7-scripts' ); ?>

<?php get_footer(); ?>
