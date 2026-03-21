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
	protected $extension_key;

	/**
	 * TYPO3 version compatibility ranges.
	 *
	 * @var array
	 */
	protected $compatibility;

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
}
