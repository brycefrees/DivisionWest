<?php
/**
 * Loop Add to Cart
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.1.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product;
$tooltip_text = "";
?>

<?php if ( ! $product->is_in_stock() ) : ?>

	<a href="<?php echo apply_filters( 'out_of_stock_add_to_cart_url', get_permalink( $product->id ) ); ?>" class="product_type_soldout" data-toggle="tooltip" data-placement="top" title="<?php _e("Sold out", "swiftframework"); ?>"><i class="sf-icon-soldout"></i><span><?php echo apply_filters( 'out_of_stock_add_to_cart_text', __( 'Out of stock', 'swiftframework' ) ); ?></span></a>
	<?php echo sf_wishlist_button(); ?>

<?php else : ?>

	<?php
		$link = array(
			'url'   => '',
			'label' => '',
			'class' => '',
			'icon' => '',
			'icon_class' => '',
		);

		

		// Wishlist Button
		echo sf_wishlist_button();

	?>

<?php endif; ?>
