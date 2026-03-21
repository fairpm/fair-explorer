<?php
namespace FairExplorer\Model;

/**
 * Class PluginInfo
 *
 * Represents detailed metadata of a WordPress plugin retrieved via the Plugin API.
 * Provides explicit getters and setters for safe and structured access.
 * Also Provides typed access to plugin fields with validation.
 */
class PluginInfo extends AssetInfo {
	/**
	 * Array of required plugin slugs (if applicable).
	 *
	 * @var array
	 */
	protected $requires_plugins;

	/**
	 * Number of unresolved support threads on WordPress.org.
	 *
	 * @var int
	 */
	protected $support_threads;

	/**
	 * Number of resolved support threads on WordPress.org.
	 *
	 * @var int
	 */
	protected $support_threads_resolved;

	/**
	 * Optional business model (if provided).
	 *
	 * @var string|null
	 */
	protected $business_model;

	// ------------------ GETTERS ------------------

	/**
	 * Get the array of required plugin slugs.
	 *
	 * @return array List of plugin slugs or empty array.
	 */
	public function get_requires_plugins() {
		return is_array( $this->requires_plugins ) ? $this->requires_plugins : [];
	}

	/**
	 * Get number of open support threads.
	 *
	 * @return int|null Open thread count.
	 */
	public function get_support_threads() {
		return is_numeric( $this->support_threads ) ? (int) $this->support_threads : null;
	}

	/**
	 * Get number of resolved support threads.
	 *
	 * @return int|null Resolved thread count.
	 */
	public function get_support_threads_resolved() {
		return is_numeric( $this->support_threads_resolved ) ? (int) $this->support_threads_resolved : null;
	}

	/**
	 * Get the business model.
	 *
	 * @return string|null business model or null.
	 */
	public function get_business_model() {
		return is_string( $this->business_model ) ? trim( $this->business_model ) : null;
	}
}
