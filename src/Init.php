<?php

namespace KarlogIT\Plugin\SaveForLater;

use KarlogIT\Plugin\SaveForLater\Frontend\{Handler, Shortcode, WooCommerce};

class Init {
	private static $instance;
	const    UserMetaKey = '_karlog_it_saveforlater';

	static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		Handler::Instance();
		Shortcode::Instance();
		WooCommerce::Instance();

		if ( is_admin() ) {
			\KarlogIT\Plugin\SaveForLater\Admin\WooCommerce::Instance();
		}
	}
}