<?php
/**
 * WooCommerce Single Product template.
 * Custom layout matching the Titan design.
 */
defined( 'ABSPATH' ) || exit;

while ( have_posts() ) :
	the_post();
	global $product;

	$in_stock  = $product->is_in_stock();
	$stock_qty = $product->get_stock_quantity();
	$img_url   = wp_get_attachment_url( $product->get_image_id() );
	if ( ! $img_url ) {
		$img_url = get_template_directory_uri() . '/assets/img/product-img.jpg';
	}
	$attributes = $product->get_attributes();
?>

<section class="product">
	<div class="container">
		<div class="grid">
			<div class="img-block">
				<div class="img"><img src="<?php echo esc_url( $img_url ); ?>" alt="<?php echo esc_attr( $product->get_name() ); ?>"></div>
				<div class="back">
					<a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
							<path d="M9 14L5 10L9 6" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
							<path d="M5 10H16C17.0609 10 18.0783 10.4214 18.8284 11.1716C19.5786 11.9217 20 12.9391 20 14C20 15.0609 19.5786 16.0783 18.8284 16.8284C18.0783 17.5786 17.0609 18 16 18H15" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
						</svg>
						Вернуться в каталог
					</a>
				</div>
			</div>
			<div class="text-block">
				<div class="name">
					<h1 class="h3"><?php echo esc_html( $product->get_name() ); ?></h1>
				</div>
				<div class="cart-area">
					<div class="price-block">
						<?php if ( $in_stock ) : ?>
							<div class="in-stock">В наличии: <span><?php echo esc_html( $stock_qty ); ?></span></div>
						<?php else : ?>
							<div class="not-in-stock">Нет в наличии</div>
						<?php endif; ?>
						<div class="price"><?php echo esc_html( $product->get_price() ); ?> ₽</div>
					</div>
					<?php if ( $in_stock ) : ?>
					<div class="btn-block">
						<div class="quantity-block">
							<div class="sign minus">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M19.001 12.998H5.00098V10.998H19.001V12.998Z" fill="white"/>
								</svg>
							</div>
							<input type="text" class="number" value="1">
							<div class="sign plus">
								<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M19 12.998H13V18.998H11V12.998H5V10.998H11V4.99805H13V10.998H19V12.998Z" fill="white"/>
								</svg>
							</div>
						</div>
						<a href="#" class="btn titan-add-to-cart" data-product-id="<?php echo esc_attr( $product->get_id() ); ?>">Добавить в корзину</a>
					</div>
					<?php else : ?>
					<div class="btn-block">
						<a href="#request-product" class="btn btn-gray popup" data-product-name="<?php echo esc_attr( $product->get_name() ); ?>">Запросить</a>
					</div>
					<?php endif; ?>
				</div>

				<?php if ( ! empty( $attributes ) ) : ?>
				<div class="characteristics">
					<?php foreach ( $attributes as $attr ) :
						$values = $attr->is_taxonomy()
							? wp_get_post_terms( $product->get_id(), $attr->get_name(), array( 'fields' => 'names' ) )
							: $attr->get_options();
					?>
					<div class="item<?php echo ( wc_attribute_label( $attr->get_name() ) === 'Материал' ) ? ' material-item' : ''; ?>">
						<div class="caption"><?php echo esc_html( wc_attribute_label( $attr->get_name() ) ); ?>:</div>
						<div class="val"><?php echo esc_html( implode( ', ', (array) $values ) ); ?></div>
					</div>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
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

<?php endwhile; ?>
