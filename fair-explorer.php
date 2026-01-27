<?php

/**
 * Fair Explorer - Fair Explorer Repository browser.
 *
 * @package     fair-explorer
 * @author      FairPM
 * @copyright   FairPM
 * @license     GPLv2
 *
 * Plugin Name:       Fair Explorer
 * Plugin URI:        https://fairpm.org/
 * Description:       Fair Explorer Repository browser.
 * Version:           0.2.0
 * Author:            FairPM
 * Author URI:        https://fairpm.org/
 * Requires at least: 5.3
 * Requires PHP:      7.4
 * Tested up to:      6.7
 * License:           GPLv2
 * License URI:       https://www.gnu.org/licenses/old-licenses/gpl-2.0.txt
 * Text Domain:       fair-explorer
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/fairpm/fair-explorer
 * Primary Branch:    main
 */

if ( ! defined( 'ABSPATH' ) ) {
	die;
}

if ( ! defined( 'AE_VERSION' ) ) {
	define( 'AE_VERSION', '0.2.0' );
}


add_action(
	'plugins_loaded',
	function () {
		if ( ! defined( 'AE_DIR_URL' ) ) {
			define( 'AE_DIR_URL', plugin_dir_url( __FILE__ ) );
		}
		if ( ! defined( 'AE_DIR_PATH' ) ) {
			define( 'AE_DIR_PATH', __DIR__ );
		}
		FairExplorer\Controller\Main::get_instance();
	}
);

require_once __DIR__ . '/includes/autoload.php';

/**
 * Register activation/deactivation hooks.
 */
register_activation_hook( __FILE__, [ 'FairExplorer\Controller\Main', 'on_activate' ] );
register_deactivation_hook( __FILE__, [ 'FairExplorer\Controller\Main', 'on_deactivate' ] );
