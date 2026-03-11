<?php
/**
 * Template Part: Project Card
 *
 * Used in archive-titan_project.php and AJAX load more.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$short_desc = get_field( 'project_short_desc' );
$permalink  = get_the_permalink();
?>
<div class="item">
	<div class="inner grid">
		<div class="text-block">
			<div class="name"><a href="<?php echo esc_url( $permalink ); ?>"><?php the_title(); ?></a></div>
			<?php if ( $short_desc ) : ?>
				<div class="text"><?php echo wp_kses_post( $short_desc ); ?></div>
			<?php endif; ?>
			<div class="more"><a href="<?php echo esc_url( $permalink ); ?>">Подробнее</a></div>
		</div>
		<?php if ( has_post_thumbnail() ) : ?>
			<div class="img">
				<a href="<?php echo esc_url( $permalink ); ?>">
					<?php the_post_thumbnail( 'large', array( 'alt' => esc_attr( get_the_title() ) ) ); ?>
				</a>
			</div>
		<?php endif; ?>
	</div>
</div>
