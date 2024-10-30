<?php

namespace KarlogIT\Plugin\SaveForLater\Admin;

use KarlogIT\Plugin\SaveForLater\Util;

class WooCommerce {
	private static $instance;
	private const  SECTION_SETTINGS = 'save-for-later';

	static function Instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	private function __construct() {
		add_filter( 'woocommerce_get_sections_products', [ $this, 'add_section' ] );
		add_filter( 'woocommerce_get_settings_products', [ $this, 'add_settings' ], 10, 2 );
	}

	function add_section( $sections ) {
		$sections[ self::SECTION_SETTINGS ] = __( 'Save for Later', 'karlog-it-save-for-later' );

		return $sections;
	}

	function add_settings( $settings, $current_section ) {
		if ( $current_section !== self::SECTION_SETTINGS ) {
			return $settings;
		}
		$settings = [];

		$settings[] = [
			'type'  => 'settings_start',
			'id'    => Util::generate_id( self::SECTION_SETTINGS, 'settings_start' ),
			'class' => 'miqid-settings miqid-' . self::SECTION_SETTINGS,
		];

		$settings[] = [
			'type' => 'title',
			'id'   => Util::generate_id( self::SECTION_SETTINGS, 'section_start' ),
			'name' => __( 'Save for Later', 'karlog-it-save-for-later' ),
		];


		$settings[] = [
			'type'     => 'checkbox',
			'id'       => Util::generate_id( self::SECTION_SETTINGS, 'auto_delete' ),
			'name'     => __( 'Auto delete', 'karlog-it-save-for-later' ),
			'desc'     => __( 'Delete when user have brought the following product.', 'karlog-it-save-for-later' ),
		];

		$settings[] = [
			'id'   => Util::generate_id( self::SECTION_SETTINGS, 'section_end' ),
			'type' => 'sectionend',
		];

		$settings[] = [
			'type' => 'settings_end',
			'id'   => Util::generate_id( self::SECTION_SETTINGS, 'settings_end' ),
		];

		return $settings;
	}
}