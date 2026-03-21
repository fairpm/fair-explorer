<?php
namespace FairExplorer\Model;

/**
 * Class ThemeInfo
 *
 * Represents detailed metadata of a WordPress theme retrieved via the Themes API.
 * Provides explicit getters and setters for safe and structured access.
 * Also provides typed access to theme fields with validation.
 */
class ThemeInfo extends AssetInfo {
	/**
	 * Screenshot image URL.
	 *
	 * @var string
	 */
	protected $screenshot_url;

	/**
	 * Theme preview URL.
	 *
	 * @var string
	 */
	protected $preview_url;

	/**
	 * Get the screenshot image URL.
	 *
	 * @return string|null Screenshot URL or null.
	 */
	public function get_screenshot_url() {
		return filter_var( $this->screenshot_url, FILTER_VALIDATE_URL ) ? $this->screenshot_url : null;
	}

	/**
	 * Get the theme preview URL.
	 *
	 * @return string|null Preview URL or null.
	 */
	public function get_preview_url() {
		return filter_var( $this->preview_url, FILTER_VALIDATE_URL ) ? $this->preview_url : null;
	}
}
