<?php
/**
 * Class Main
 *
 * The Main Workflow Controller for the Plugin.
 */

namespace FairExplorer\Controller;

use PHPCSStandards\Composer\Plugin\Installers\PHPCodeSniffer\Plugin;

class Main extends \FairExplorer\Model\Singleton {
	/**
	 * Constructor.
	 */
	protected function init() {
		add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );
		new Packages( 'plugins' );
		new Packages( 'themes' );
		Playground::get_instance();
	}

	public function wp_enqueue_scripts() {
		wp_enqueue_style( 'dashicons' );
		wp_enqueue_style(
			'fair-explorer-styles',
			AE_DIR_URL . 'assets/css/fair-explorer.css',
			[],
			filemtime( AE_DIR_PATH . '/assets/css/fair-explorer.css' )
		);
		wp_enqueue_script(
			'fair-explorer-scripts',
			AE_DIR_URL . 'assets/js/fair-explorer.js',
			[ 'jquery' ],
			filemtime( AE_DIR_PATH . '/assets/js/fair-explorer.js' ),
			true
		);
	}

	/**
	 * Activate plugin: flush rewrite rules
	 */
	public static function on_activate() {
		new Packages( 'plugins' );
		new Packages( 'themes' );
		Playground::get_instance();
		flush_rewrite_rules();
	}

	/**
	 * Deactivate plugin: flush rewrite rules
	 */
	public static function on_deactivate() {
		flush_rewrite_rules();
	}
}
