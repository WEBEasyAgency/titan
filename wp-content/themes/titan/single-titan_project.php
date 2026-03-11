<?php
/**
 * Single Template: Проект (titan_project)
 */
get_header();

$top_image  = get_field( 'project_top_image' );
$main_image = get_field( 'project_main_image' );
$gallery    = get_field( 'project_gallery' );
$archive_url = get_post_type_archive_link( 'titan_project' );
?>

<main class="inner-page project-detail-page">
	<section class="project-detail">
		<div class="container">
			<?php if ( $top_image ) : ?>
				<div class="top-img">
					<img src="<?php echo esc_url( $top_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>">
					<div class="back">
						<a href="<?php echo esc_url( $archive_url ); ?>">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M9 14L5 10L9 6" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
								<path d="M5 10H16C17.0609 10 18.0783 10.4214 18.8284 11.1716C19.5786 11.9217 20 12.9391 20 14C20 15.0609 19.5786 16.0783 18.8284 16.8284C18.0783 17.5786 17.0609 18 16 18H15" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							</svg>
							Вернуться к проектам
						</a>
					</div>
				</div>
			<?php else : ?>
				<div class="back" style="margin-bottom: 24px;">
					<a href="<?php echo esc_url( $archive_url ); ?>">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M9 14L5 10L9 6" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 10H16C17.0609 10 18.0783 10.4214 18.8284 11.1716C19.5786 11.9217 20 12.9391 20 14C20 15.0609 19.5786 16.0783 18.8284 16.8284C18.0783 17.5786 17.0609 18 16 18H15" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						Вернуться к проектам
					</a>
				</div>
			<?php endif; ?>

			<div class="project-container grid">
				<div class="text-block">
					<?php the_content(); ?>
				</div>

				<?php if ( $main_image || ! empty( $gallery ) ) : ?>
					<div class="img-block">
						<?php if ( $main_image ) : ?>
							<div class="img"><img src="<?php echo esc_url( $main_image ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>"></div>
						<?php endif; ?>

						<?php if ( ! empty( $gallery ) ) : ?>
							<div class="img-slider-block">
								<div class="img-slider">
									<div class="swiper-wrapper">
										<?php foreach ( $gallery as $image_url ) : ?>
											<div class="swiper-slide"><img src="<?php echo esc_url( $image_url ); ?>" alt=""></div>
										<?php endforeach; ?>
									</div>
								</div>
								<div class="arrows-block">
									<div class="arrow prev">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M17.5098 3.86998L15.7298 2.09998L5.83984 12L15.7398 21.9L17.5098 20.13L9.37984 12L17.5098 3.86998Z" fill="black"/>
										</svg>
									</div>
									<div class="arrow next">
										<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path d="M6.49016 3.86998L8.27016 2.09998L18.1602 12L8.26016 21.9L6.49016 20.13L14.6202 12L6.49016 3.86998Z" fill="black"/>
										</svg>
									</div>
								</div>
							</div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>
	</section>

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

<?php get_template_part( 'template-parts/cf7-scripts' ); ?>

<?php get_footer(); ?>
