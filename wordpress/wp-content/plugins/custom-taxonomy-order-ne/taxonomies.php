<?php


function custom_taxonomy_order() {

	// Set your custom capability through this filter.
	$custom_cap = apply_filters( 'customtaxorder_custom_cap', 'manage_categories' );

	if ( ! current_user_can( $custom_cap ) ) {
		die(esc_html__( 'You need a higher level of permission.', 'custom-taxonomy-order-ne' ));
	}

	if (isset($_POST['order-submit'])) {
		customtaxorder_update_taxonomies();
	}

	?>
	<div class='wrap customtaxorder'>
		<div id="icon-customtaxorder"></div>
		<h1><?php esc_html_e('Order Taxonomies', 'custom-taxonomy-order-ne'); ?></h1>

		<form name="custom-order-form" method="post" action=""><?php

			/* Nonce */
			$nonce = wp_create_nonce( 'custom-taxonomy-order-ne-nonce' );
			echo '<input type="hidden" id="custom-taxonomy-order-ne-nonce" name="custom-taxonomy-order-ne-nonce" value="' . esc_attr( $nonce ) . '" />';

			$taxonomies = customtaxorder_get_taxonomies() ;

			if ( ! empty($taxonomies) ) {

				$taxonomies_ordered = customtaxorder_sort_taxonomies( $taxonomies );
				?>

				<div id="poststuff" class="metabox-holder">
					<div class="widget order-widget">
						<h2 class="widget-top">
							<?php esc_html_e('Order Taxonomies', 'custom-taxonomy-order-ne'); ?> |
							<small><?php esc_html_e('Order the taxonomies by dragging and dropping them into the desired order.', 'custom-taxonomy-order-ne') ?></small>
						</h2>
						<div class="misc-pub-section">
							<ul id="custom-taxonomy-list">
								<?php
								foreach ( $taxonomies_ordered as $taxonomy ) {
									$tax_label = $taxonomy->label;
									if ( ! isset( $tax_label ) || strlen( $tax_label ) === 0 ) {
										$tax_label = $taxonomy->name;
									}
									$tax_name = $taxonomy->name;

									?>
									<li id="<?php echo esc_attr( $tax_name ); ?>" class="lineitem"><?php echo esc_html( $tax_label ) . ' &nbsp;(' . esc_html( $tax_name ) . ')';?></li>
								<?php
								} ?>
							</ul>
						</div>
						<div class="misc-pub-section misc-pub-section-last">
							<div id="publishing-action">
								<img src="<?php echo esc_url( admin_url( 'images/wpspin_light.gif' ) ); ?>" id="custom-loading" style="display:none" alt="" />
								<input type="submit" name="order-submit" id="order-submit" class="button-primary" value="<?php esc_attr_e('Update Order', 'custom-taxonomy-order-ne') ?>" />
							</div>
							<div class="clear"></div>
						</div>
						<input type="hidden" id="hidden-taxonomy-order" name="hidden-taxonomy-order" />
					</div>
				</div>

			<?php } else { ?>
				<p><?php esc_html_e('No taxonomies found', 'custom-taxonomy-order-ne'); ?></p>
			<?php }
			?>
		</form>
	</div>
	<?php
}


/*
 * Save order of the taxonomies in an option.
 */
function customtaxorder_update_taxonomies() {

	/* Check Nonce */
	$verified = false;
	if ( isset($_POST['custom-taxonomy-order-ne-nonce']) ) {
		$verified = wp_verify_nonce( $_POST['custom-taxonomy-order-ne-nonce'], 'custom-taxonomy-order-ne-nonce' );
	}
	if ( $verified == false ) {
		// Nonce is invalid.
		echo '<div id="message" class="error fade notice is-dismissible"><p>' . esc_html__('The Nonce did not validate. Please try again.', 'custom-taxonomy-order-ne') . '</p></div>';
		return;
	}

	// Set your custom capability through this filter.
	$custom_cap = apply_filters( 'customtaxorder_custom_cap', 'manage_categories' );

	if ( ! current_user_can( $custom_cap ) ) {
		die(esc_html__( 'You need a higher level of permission.', 'custom-taxonomy-order-ne' ));
	}

	if ( isset( $_POST['hidden-taxonomy-order'] ) && $_POST['hidden-taxonomy-order'] != '' ) {

		$new_order = sanitize_text_field( $_POST['hidden-taxonomy-order'] );
		update_option('customtaxorder_taxonomies', $new_order);

		echo '<div id="message" class="updated fade notice is-dismissible"><p>'. esc_html__('Order updated successfully.', 'custom-taxonomy-order-ne').'</p></div>';
	} else {
		echo '<div id="message" class="error fade notice is-dismissible"><p>'. esc_html__('An error occured, order has not been saved.', 'custom-taxonomy-order-ne').'</p></div>';
	}

}
function customtaxorder_taxonomies_validate( $input ) {

	$input = (string) sanitize_text_field( $input );
	return $input;

}

/*
 * Sort the taxonomies.
 *
 * @param $taxonomies, array with a list of taxonomy objects.
 *
 * @return array list of taxonomies, ordered correctly.
 *
 * @since: 2.7.0
 *
 */
function customtaxorder_sort_taxonomies( $taxonomies = array() ) {
	$order = get_option( 'customtaxorder_taxonomies', '' );
	$order = explode( ',', $order );
	$taxonomies_ordered = array();

	// Main sorted taxonomies.
	if ( ! empty($order) && is_array($order) && ! empty($taxonomies) && is_array($taxonomies) ) {
		foreach ( $order as $tax ) {
			foreach ( $taxonomies as $tax_name => $tax_obj ) {
				if ( is_object( $tax_obj ) && $tax === $tax_name ) {
					$taxonomies_ordered[ $tax_name ] = $tax_obj;
					unset( $taxonomies[ $tax_name ] );
				}
			}
		}
	}

	// Unsorted taxonomies, the leftovers.
	foreach ( $taxonomies as $tax_name => $tax_obj ) {
		$taxonomies_ordered[ $tax_name ] = $tax_obj;
	}

	return $taxonomies_ordered;
}


/*
 * Sort the taxonomies for WooCommerce automatically.
 *
 * @param $attributes array with a list of taxonomy objects of WC_Product_Attribute.
 *
 * @rturn array list of taxonomies, ordered correctly.
 *
 * @since: 2.10.0
 *
 */
function customtaxorder_sort_woocommerce_taxonomies( $attributes ) {
	if ( is_array( $attributes ) && ! empty( $attributes ) ) {
		foreach ( $attributes as $attribute ) {
			if ( is_object( $attribute ) && is_a( $attribute, 'WC_Product_Attribute' ) ) {
				// nothing to do
			} else {
				return $attributes; // not an attribute we are looking for.
			}
		}
		// We have the correct data.
		$attributes = customtaxorder_sort_taxonomies( $attributes );
	}

	return $attributes;
}
add_filter( 'woocommerce_product_get_attributes', 'customtaxorder_sort_woocommerce_taxonomies' );
