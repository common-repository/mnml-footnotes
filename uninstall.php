<?php
/**
 * This file will be executed on plugin deletion.
 *
 * It removes anything from the database registered by this plugin.
 *
 * @package minimal-footnotes
 */

// If uninstall is not called from WordPress, exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit();
}
