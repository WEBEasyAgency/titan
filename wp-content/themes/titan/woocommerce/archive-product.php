<?php
/**
 * WooCommerce Shop / Archive Product template.
 * Replaces the default WC product archive with custom catalog layout.
 */
defined( 'ABSPATH' ) || exit;

$check_svg = '<svg width="9" height="7" viewBox="0 0 9 7" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M7.41667 0.75L2.75 6.08333L0.75 3.95" stroke="#1E1E1E" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/></svg>';

$parent_categories = get_terms( array(
	'taxonomy'   => 'product_cat',
	'hide_empty' => false,
	'parent'     => 0,
	'exclude'    => array( get_option( 'default_product_cat' ) ),
	'orderby'    => 'name',
	'order'      => 'ASC',
) );
?>

<section class="top-search">
	<div class="container">
		<form action="/" method="post" id="titan-product-search-form">
			<div class="search-block">
				<div class="search-field">
					<div class="search">
						<input type="search" placeholder="Поиск" id="titan-search-input">
						<div class="clean">
							<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
								<path d="M11.9998 13.4L7.0998 18.3C6.91647 18.4834 6.68314 18.575 6.3998 18.575C6.11647 18.575 5.88314 18.4834 5.6998 18.3C5.51647 18.1167 5.4248 17.8834 5.4248 17.6C5.4248 17.3167 5.51647 17.0834 5.6998 16.9L10.5998 12L5.6998 7.10005C5.51647 6.91672 5.4248 6.68338 5.4248 6.40005C5.4248 6.11672 5.51647 5.88338 5.6998 5.70005C5.88314 5.51672 6.11647 5.42505 6.3998 5.42505C6.68314 5.42505 6.91647 5.51672 7.0998 5.70005L11.9998 10.6L16.8998 5.70005C17.0831 5.51672 17.3165 5.42505 17.5998 5.42505C17.8831 5.42505 18.1165 5.51672 18.2998 5.70005C18.4831 5.88338 18.5748 6.11672 18.5748 6.40005C18.5748 6.68338 18.4831 6.91672 18.2998 7.10005L13.3998 12L18.2998 16.9C18.4831 17.0834 18.5748 17.3167 18.5748 17.6C18.5748 17.8834 18.4831 18.1167 18.2998 18.3C18.1165 18.4834 17.8831 18.575 17.5998 18.575C17.3165 18.575 17.0831 18.4834 16.8998 18.3L11.9998 13.4Z" fill="#231F20"/>
							</svg>
						</div>
					</div>
					<div class="btn-block"><button type="submit">Найти</button></div>
				</div>
				<div class="search-result" id="titan-search-results"></div>
			</div>
		</form>
	</div>
</section>

<section class="catalog-block">
	<div class="container">
		<div class="catalog-grid">
			<div class="left-menu">
				<div class="menu-block">
					<div class="caption">Категории</div>
					<div class="category-list">
						<div class="item">
							<label class="radio">
								<input type="radio" name="cat" data-cat-id="0" checked>
								<span class="check"></span>
								<span class="label">Все</span>
							</label>
						</div>
						<?php if ( ! is_wp_error( $parent_categories ) ) :
							foreach ( $parent_categories as $cat ) :
								$children = get_terms( array(
									'taxonomy'   => 'product_cat',
									'hide_empty' => false,
									'parent'     => $cat->term_id,
									'orderby'    => 'name',
									'order'      => 'ASC',
								) );
								$has_children = ! is_wp_error( $children ) && ! empty( $children );
								?>
								<div class="item<?php echo $has_children ? ' parent' : ''; ?>">
									<label class="radio">
										<input type="radio" name="cat" data-cat-id="<?php echo esc_attr( $cat->term_id ); ?>">
										<span class="check"></span>
										<span class="label"><?php echo esc_html( $cat->name ); ?></span>
									</label>
									<?php if ( $has_children ) : ?>
										<div class="subcategories">
											<div class="list">
												<div class="list-item">
													<label class="checkbox">
														<input type="checkbox" name="sub" data-subcat-id="0" data-select-all="1">
														<span class="check"><?php echo $check_svg; ?></span>
														<span class="label">Все</span>
													</label>
												</div>
												<?php foreach ( $children as $child ) : ?>
													<div class="list-item">
														<label class="checkbox">
															<input type="checkbox" name="sub" data-subcat-id="<?php echo esc_attr( $child->term_id ); ?>">
															<span class="check"><?php echo $check_svg; ?></span>
															<span class="label"><?php echo esc_html( $child->name ); ?></span>
														</label>
													</div>
												<?php endforeach; ?>
											</div>
											<?php if ( count( $children ) > 6 ) : ?>
												<a href="#" class="show-all">Показать все</a>
											<?php endif; ?>
										</div>
									<?php endif; ?>
								</div>
							<?php endforeach;
						endif; ?>
					</div>
				</div>
			</div>
			<?php echo titan_render_catalog_table(); ?>
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
