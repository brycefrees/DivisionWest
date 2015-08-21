<?php
	/**
	 * The template for displaying product content within loops.
	 *
	 * Override this template by copying it to yourtheme/woocommerce/content-product.php
	 *
	 * @author 		WooThemes
	 * @package 	WooCommerce/Templates
	 * @version     1.6.4
	 */

	if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

	global $woocommerce, $post, $product, $woocommerce_loop, $sf_options, $sf_catalog_mode, $sf_product_multimasonry, $sf_product_display_type;

	// Store loop count we're currently on
	if ( empty( $woocommerce_loop['loop'] ) )
		$woocommerce_loop['loop'] = 0;

	// Store column count for displaying the grid
	if ( empty( $woocommerce_loop['columns'] ) ) {
		$product_display_columns = $sf_options['product_display_columns'];

		// COLUMNS GET VARIABLE
		if (isset($_GET['product_columns'])) {
			$product_display_columns = $_GET['product_columns'];
		}

		$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', $product_display_columns );
	}

	// Ensure visibility
	if ( ! $product || ! $product->is_visible() )
		return;

	// Increase loop count
	$woocommerce_loop['loop']++;

	// Extra post classes
	$classes = array();
	if ( 0 == ( $woocommerce_loop['loop'] - 1 ) % $woocommerce_loop['columns'] || 1 == $woocommerce_loop['columns'] )
		$classes[] = 'first';
	if ( 0 == $woocommerce_loop['loop'] % $woocommerce_loop['columns'] )
		$classes[] = 'last';

	$width = $thumb_width = $thumb_height = "";

	if ( $sf_product_multimasonry ) {

		$masonry_thumb_size = sf_get_post_meta( get_the_ID(), 'sf_masonry_thumb_size', true );

		if ( $masonry_thumb_size == "large" ) {
		    $classes[] = 'col-sm-6 size-large';
		    $width = 'col-sm-6';
		    $thumb_width = 800;
		    $thumb_height = 650;
		} else if ( $masonry_thumb_size == "tall" ) {
		    $classes[] = 'col-sm-3 size-tall';
		    $width = 'col-sm-3';
		    $thumb_width = 400;
		    $thumb_height = 800;
		} else {
			$classes[] = 'col-sm-3 size-standard';
			$width = 'col-sm-3';
			$thumb_width = 400;
			$thumb_height = 320;
		}

	} else {

		if ($woocommerce_loop['columns'] == 4) {
			$classes[] = 'col-sm-3';
			$width = 'col-sm-3';
		} else if ($woocommerce_loop['columns'] == 5) {
			$classes[] = 'col-sm-sf-5';
			$width = 'col-sm-sf-5';
		} else if ($woocommerce_loop['columns'] == 3) {
			$classes[] = 'col-sm-4';
			$width = 'col-sm-4';
		} else if ($woocommerce_loop['columns'] == 2) {
			$classes[] = 'col-sm-6';
			$width = 'col-sm-6';
		} else if ($woocommerce_loop['columns'] == 6) {
			$classes[] = 'col-sm-2';
			$width = 'col-sm-2';
		}

	}

	$product_display_type = $sf_options['product_display_type'];
	$product_display_gutters = $sf_options['product_display_gutters'];
	$product_qv_hover = $sf_options['product_qv_hover'];
	$product_buybtn = $sf_options['product_buybtn'];
	$product_rating = $sf_options['product_rating'];
	$product_details_alignment = $sf_options['product_details_alignment'];

	if ( $sf_product_display_type ) {
		$product_display_type = $sf_product_display_type;
	}

	// GET VARIABLES
	if ( isset($_GET['product_display']) ) {
		$product_display_type = $_GET['product_display'];
	}
	if ( isset($woocommerce_loop['style-override']) && $woocommerce_loop['style-override'] != "" ) {
		$product_display_type = $woocommerce_loop['style-override'];
	}

	if ( isset($_GET['product_gutters']) ) {
		$product_display_gutters = $_GET['product_gutters'];
	}

	if ( $sf_product_multimasonry ) {
		$product_display_type = "gallery";
	}

	if ( $product_qv_hover ) {
		$classes[] = 'qv-hover';
	}

	$product_layout = "standard";
	$figure_class = 'animated-overlay';

	if (isset($_GET['sidebar'])) {
		$sidebar_config = $_GET['sidebar'];
	}

	if (isset($_GET['layout'])) {
		$product_layout = $_GET['layout'];
	}

	if ( $product_display_type == "standard" ) {
		$figure_class .= ' product-transition-fade';
	} else {
		$figure_class .= ' product-transition-zoom';
	}

	$classes[] = 'product-display-'.$product_display_type;

	if (!$product_display_gutters && $product_display_type == "gallery") {
		$classes[] = 'no-gutters';
	}

	if ($product_buybtn && $product_display_type == "standard") {
		$classes[] = 'buy-btn-visible';
	}
	if ($product_rating && $product_display_type == "standard") {
		$classes[] = 'rating-visible';
	}

	$classes[] = 'product-layout-'.$product_layout;

	$classes[] = 'details-align-'.$product_details_alignment;

	$product_description = sf_get_post_meta($post->ID, 'sf_product_short_description', true);
	if ($product_description == "") {
		$product_description = $post->post_excerpt;
	}
?>
<div <?php post_class( $classes ); ?> data-width="<?php echo esc_attr($width); ?>">

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<figure class="<?php echo esc_attr($figure_class); ?>">

		<?php sf_woo_product_badge(); ?>

		<?php if ( $sf_product_multimasonry ) {
			$thumb_image    = get_post_thumbnail_id();
			$thumb_image_id = $thumb_image;
			$thumb_img_url  = wp_get_attachment_url( $thumb_image, 'full' );
			$image = sf_aq_resize( $thumb_img_url, $thumb_width, $thumb_height, true, false );
			$image_alt = esc_attr( sf_get_post_meta( $thumb_image_id, '_wp_attachment_image_alt', true ) );
			
			if ($image_alt == "") {
				$image_alt = get_the_title();
			}

			if ( $image ) {
				echo '<div class="multi-masonry-img-wrap"><img src="' . $image[0] . '" width="' . $image[1] . '" height="' . $image[2] . '" alt="' . $image_alt . '" /></div>' . "\n";
			}

		} else {
			echo '<div class="img-wrap first-image">';
			woocommerce_template_loop_product_thumbnail();
			echo '</div>';

			if ($product_display_type == "standard") {

				$attachment_ids = $product->get_gallery_attachment_ids();

				$img_count = 0;

				if ($attachment_ids) {

					foreach ( $attachment_ids as $attachment_id ) {

						if ( sf_get_post_meta( $attachment_id, '_woocommerce_exclude_image', true ) )
							continue;

						echo '<div class="img-wrap second-image">'.wp_get_attachment_image( $attachment_id, 'shop_catalog' ).'</div>';

						$img_count++;

						if ($img_count == 1) break;

					}

				} else {
					echo '<div class="img-wrap second-image">';
					woocommerce_template_loop_product_thumbnail();
					echo '</div>';
				}
			}
		} ?>

		<?php if (!$sf_catalog_mode) { ?>
		<div class="cart-overlay">
			<div class="shop-actions clearfix">
				<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
			</div>
		</div>
		<?php } ?>

		<a href="<?php the_permalink(); ?>"></a>

		<div class="figcaption-wrap"></div>

		<?php if ($product_display_type != "standard") { ?>
			<figcaption>
				<div class="thumb-info">
					<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
					<h4><?php the_title(); ?></h4>
					<?php
						$size = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
						echo $product->get_categories( ', ', '<h5 class="posted_in">', '</h5>' );
					?>
					<h6><?php woocommerce_template_loop_price(); ?></h6>
				</div>
			</figcaption>

		<?php } ?>

	</figure>

	<div class="product-details">
		<?php do_action( 'woocommerce_before_shop_loop_item_title' ); ?>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php
			$size = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
			echo $product->get_categories( ', ', '<span class="posted_in">', '</span>' );
		?>

		<div class="product-desc">
			<?php echo esc_attr($product_description); ?>
		</div>

		<?php
			/**
			 * woocommerce_after_shop_loop_item_title hook
			 *
			 * @hooked woocommerce_template_loop_price - 10
			 */
			do_action( 'woocommerce_after_shop_loop_item_title' );
		?>

	</div>

	<?php if ($product_display_type == "standard") { ?>
		<div class="clear"></div>
		<div class="product-actions">
			<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>
		</div>
	<?php } ?>


</div>