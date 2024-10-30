<?php

namespace KarlogIT\Plugin\SaveForLater\Frontend;

use KarlogIT\Plugin\SaveForLater\Init;

class WooCommerce {
	private static $_instance;

	public static function Instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	private function __construct() {
		add_action( 'woocommerce_thankyou', [ $this, '_thankyou' ], 10, 1 );

		add_action( 'woocommerce_product_meta_end', [ $this, 'Button' ] );
	}

	function _thankyou( $order_id ) {
		$auto_delete = filter_var( get_option( 'miqid_woo_save-for-later_auto_delete' ), FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE );
		if ( ! $auto_delete ) {
			return;
		}
		/** @var array $saved_products */
		$saved_products = get_user_meta( get_current_user_id(), Init::UserMetaKey, true );

		if ( empty( $saved_products ) ) {
			return;
		}

		$order = wc_get_order( $order_id );
		/**
		 * @var \WC_Order_Item_Product $order_item
		 */
		foreach ( $order->get_items() as $order_item ) {
			$product_id = $order_item->get_product_id();
			if ( isset( $saved_products[ $product_id ] ) ) {
				unset( $saved_products[ $product_id ] );
			}
		}

		update_user_meta( get_current_user_id(), Init::UserMetaKey, $saved_products );
	}

	function Button() {
		global $product;

		$saved_products = get_user_meta( get_current_user_id(), Init::UserMetaKey, true );

		if ( empty( $saved_products ) ) {
			$saved_products = [];
		}

		printf( '<form action="%1$s" method="post">
    %2$s
    <input type="hidden" name="action" value="%3$s">
    <input type="hidden" name="product_id" value="%4$s">
    <button name="method" type="submit" class="button button-primary button-small" value="%6$s">%5$s</button>
</form>',
			esc_url( admin_url( 'admin-post.php' ) ),
			wp_nonce_field( Init::UserMetaKey, '_wpnonce', true, false ),
			Init::UserMetaKey,
			$product->get_id(),
			isset( $saved_products[ $product->get_id() ] ) ? __( 'Remove from Later', 'karlog-it-save-for-later' ) : __( 'Save for Later', 'karlog-it-save-for-later' ),
			isset( $saved_products[ $product->get_id() ] ) ? 'remove' : 'save'
		);
	}
}