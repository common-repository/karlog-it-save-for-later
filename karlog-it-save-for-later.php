<?php
/**
 * Plugin Name:       Karlog-IT - Save-for-Later
 * Description:       Extended WooCommerce with Save for Later
 * Version:           1.2.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Karlog-IT
 * Author URI:        https://karlog-it.dk
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Text Domain:       karlog-it-save-for-later
 */

require_once __DIR__ . '/vendor/autoload.php';

load_plugin_textdomain( 'karlog-it-save-for-later', false, basename( dirname( __FILE__ ) ) . '/languages/' );

\KarlogIT\Plugin\SaveForLater\Init::Instance();