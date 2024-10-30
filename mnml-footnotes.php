<?php
/**
 * Plugin Name: MNML Footnotes
 * Description: Auto-listed footnotes for posts.
 * Version:     0.3.0
 * Author:      Mauro Bringolf
 * Author URI:  https://maurobringolf.ch
 * License:     GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Domain Path: /lang/
 * Text Domain: mnml-footnotes
 *
 * @package MNML_Footnotes
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Check WordPress and PHP version compatibility.
include_once __DIR__ . '/lib/class-mnml-version-check.php';

$mbch_mnml_footnotes_version_check = new MNML_Version_Check(array(
	'name'       => 'MNML Footnotes',
	'php'        => '5.3',
	'wp'         => '3.0',
	'textdomain' => 'mnml-footnotes',
	'file'       => __FILE__,
));

$mbch_mnml_footnotes_version_check->check();

// Load the main plugin class.
include_once __DIR__ . '/src/class-main.php';

// Start the plugin.
MauroBringolf\MNML_Footnotes\Main::instance( __FILE__ )->run();

/**
 * Provides access to the plugin instance.
 *
 * @return MauroBringolf\MNML_Footnotes\Main
 */
function mbch_mnml_footnotes() {
	return MauroBringolf\MNML_Footnotes\Main::instance( __FILE__ );
}
