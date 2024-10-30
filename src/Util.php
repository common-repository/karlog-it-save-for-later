<?php

namespace KarlogIT\Plugin\SaveForLater;

class Util {
	static function generate_id( ...$name ) {
		return trim( sprintf( 'miqid_woo_%s', implode( '_', $name ) ), " \t\n\r\0\x0B\_" );
	}
}