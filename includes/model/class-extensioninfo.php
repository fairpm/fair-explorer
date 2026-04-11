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
	 * License identifier (e.g. 'GPL-2.0-or-later').
	 *
	 * @var string|null
	 */
	protected $license;

	/**
	 * Package type (e.g. 'typo3-extension').
	 *
	 * @var string|null
	 */
	protected $type;

	/**
	 * Authors array (each element has at least a 'name' key).
	 *
	 * @var array
	 */
	protected $authors;

	/**
	 * Full releases data from the API.
	 *
	 * @var array
	 */
	protected $releases;

	/**
	 * Security contact information.
	 *
	 * @var array
	 */
	protected $security;

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
	 * Get the license identifier.
	 *
	 * @return string|null License string or null.
	 */
	public function get_license() {
		return is_string( $this->license ) && '' !== $this->license ? trim( $this->license ) : null;
	}

	/**
	 * Get the package type.
	 *
	 * @return string|null Type string or null.
	 */
	public function get_type() {
		return is_string( $this->type ) && '' !== $this->type ? trim( $this->type ) : null;
	}

	/**
	 * Get all authors.
	 *
	 * @return array Authors array or empty array.
	 */
	public function get_authors() {
		return is_array( $this->authors ) ? $this->authors : [];
	}

	/**
	 * Get all releases.
	 *
	 * @return array Releases array or empty array.
	 */
	public function get_releases() {
		return is_array( $this->releases ) ? $this->releases : [];
	}

	/**
	 * Get the number of releases.
	 *
	 * @return int Release count.
	 */
	public function get_release_count() {
		return is_array( $this->releases ) ? count( $this->releases ) : 0;
	}

	/**
	 * Get security contact information.
	 *
	 * @return array Security contacts or empty array.
	 */
	public function get_security_contacts() {
		return is_array( $this->security ) ? $this->security : [];
	}
}
