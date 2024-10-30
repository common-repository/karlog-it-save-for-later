<?php

namespace KarlogIT\Plugin\SaveForLater\Frontend;

use KarlogIT\Plugin\SaveForLater\Init;

class Handler {
	private static $instance;

	static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_post_' . Init::UserMetaKey, [ $this, 'save_remove' ] );
		add_action( 'admin_post_nopriv_' . Init::UserMetaKey, [ $this, 'save_remove' ] );
	}

	function save_remove() {
		$saved_products = get_user_meta( get_current_user_id(), Init::UserMetaKey, true );
		if ( empty( $saved_products ) ) {
			$saved_products = [];
		}

		$wp_verify_nonce = wp_verify_nonce( sanitize_text_field( $_POST['_wpnonce'] ), Init::UserMetaKey );

		if ( ! $wp_verify_nonce ) {
			wp_die( 'Something went wrong' );
		}

		$method           = mb_strtolower( sanitize_text_field( $_POST['method'] ?? '' ) );
		$product_id       = filter_var( sanitize_text_field( $_POST['product_id'] ?? '' ), FILTER_VALIDATE_INT, FILTER_NULL_ON_FAILURE ) ?? 0;
		$_wp_http_referer = sanitize_text_field( $_POST['_wp_http_referer'] );

		switch ( $method ) {
			case 'save':
				$saved_products[ $product_id ] = true;
				break;
			case 'remove':
				unset( $saved_products[ $product_id ] );
				break;
		}

		update_user_meta( get_current_user_id(), Init::UserMetaKey, $saved_products );

		wp_redirect( $_wp_http_referer );

	}
}