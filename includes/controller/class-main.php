<?php
/**
 * Class Main
 *
 * The Main Workflow Controller for the Plugin.
 */

namespace FairExplorer\Controller;

class Main extends \FairExplorer\Model\Singleton {
	/**
	 * Constructor.
	 */
	protected function init() {
		add_action( 'wp_enqueue_scripts', [ $this, 'wp_enqueue_scripts' ] );

		foreach ( self::get_platforms() as $config ) {
			new Packages( $config );
		}
		Playground::get_instance();
	}

	/**
	 * Build the platform registry.
	 *
	 * Returns WordPress defaults (plugins + themes) and allows external code
	 * to register additional platforms via the 'fair_explorer_platforms' filter.
	 *
	 * @return array[] List of platform config arrays.
	 */
	public static function get_platforms() {
		$root = defined( 'AE_ROOT' ) ? trim( AE_ROOT, '/' ) : '';

		$platforms = [
			[
				'asset_type'  => 'plugins',
				'root'        => $root,
				'model_class' => 'FairExplorer\Model\PluginInfo',
				'fetcher'     => null,
			],
			[
				'asset_type'  => 'themes',
				'root'        => $root,
				'model_class' => 'FairExplorer\Model\ThemeInfo',
				'fetcher'     => null,
			],
			[
				'asset_type'  => 'extensions',
				'root'        => 'packages/typo3',
				'slug_var'    => 'extension_slug',
				'model_class' => 'FairExplorer\Model\ExtensionInfo',
				'fetcher'     => [ Typo3::class, 'fetch' ],
			],
		];

		/**
		 * Filter the list of platforms registered in Fair Explorer.
		 *
		 * Each platform is an associative array with keys:
		 *   'asset_type'  (string) Plural name — used for views dir, results property.
		 *   'root'        (string) URL prefix.
		 *   'slug_var'    (string) Optional query var (derived if absent: {singular}_slug).
		 *   'model_class' (string) FQCN of the model class extending AssetInfo.
		 *   'fetcher'     (callable|null) Custom data fetcher or null for WP core API.
		 *
		 * @param array[] $platforms Default platform configs.
		 */
		return apply_filters( 'fair_explorer_platforms', $platforms );
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
		foreach ( self::get_platforms() as $config ) {
			new Packages( $config );
		}
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
