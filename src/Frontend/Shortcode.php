<?php

namespace KarlogIT\Plugin\SaveForLater\Frontend;

use KarlogIT\Plugin\SaveForLater\Init;

class Shortcode {
	private static $instance;

	static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_shortcode( 'karlog_it_wsfl_products', [ $this, 'products' ] );
	}

	function products( $atts ) {
		$saved_products = get_user_meta( get_current_user_id(), Init::UserMetaKey, true );
		if ( empty( $saved_products ) ) {
			return '';
		}

		return do_shortcode( sprintf( '[products ids="%s"]', implode( ',', array_keys( $saved_products ) ) ) );
	}
}