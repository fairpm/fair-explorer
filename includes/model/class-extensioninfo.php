<?php
namespace FairExplorer\Model;

/**
 * Class ExtensionInfo
 *
 * Represents detailed metadata of a TYPO3 extension.
 * Provides explicit getters for safe and structured access.
 */
class ExtensionInfo extends AssetInfo {
	/**
	 * TYPO3 extension key (e.g. 'news', 'powermail').
	 *
	 * @var string|null
	 */
	private $extension_key;

	/**
	 * TYPO3 version compatibility ranges.
	 *
	 * @var array
	 */
	private $compatibility;

	/**
	 * Icon URLs (keys may include '1x', '2x', 'svg').
	 *
	 * @var array
	 */
	private $icons;

	public function __construct( $data = [] ) {
		parent::__construct( $data );
		foreach ( $data as $key => $value ) {
			if ( property_exists( $this, $key ) ) {
				$this->$key = $value;
			}
		}
	}

	/**
	 * Get the TYPO3 extension key.
	 *
	 * @return string|null Extension key or null.
	 */
	public function get_extension_key() {
		return is_string( $this->extension_key ) && '' !== $this->extension_key ? trim( $this->extension_key ) : null;
	}

	/**
	 * Get TYPO3 version compatibility information.
	 *
	 * @return array Compatibility data or empty array.
	 */
	public function get_compatibility() {
		return is_array( $this->compatibility ) ? $this->compatibility : [];
	}

	/**
	 * Get all icons or a specific size.
	 *
	 * @param string|null $size Optional: 'svg', '2x', '1x'
	 * @return array|string|null All icons or specific.
	 */
	public function get_icons( $size = null ) {
		if ( ! is_array( $this->icons ) ) {
			return null === $size ? [] : null;
		}
		return null === $size ? $this->icons : ( $this->icons[ $size ] ?? null );
	}

	/**
	 * Get the best available icon URL by priority (svg > 2x > 1x).
	 *
	 * @return string|null The best icon URL or null.
	 */
	public function get_best_icon() {
		foreach ( [ 'svg', '2x', '1x', 'default' ] as $size ) {
			$icon = $this->get_icons( $size );
			if ( $icon && filter_var( $icon, FILTER_VALIDATE_URL ) ) {
				return $icon;
			}
		}
		return null;
	}
}
