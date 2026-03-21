<?php
/**
 * Class Typo3
 *
 * TYPO3 platform integration for Fair Explorer.
 * Registers the extensions asset type and fetches data from AspireCloud.
 */

namespace FairExplorer\Controller\Typo3;

class Typo3 {
	/**
	 * Default AspireCloud API base URL.
	 */
	const DEFAULT_API_URL = 'https://api.aspirecloud.net';

	/**
	 * Get the AspireCloud API base URL.
	 *
	 * Defaults to api.aspirecloud.net. Filterable via 'fair_explorer_typo3_api_url'
	 * so AspireUpdate or other plugins can point to a different host.
	 *
	 * @return string API base URL (no trailing slash).
	 */
	private static function get_api_url() {
		$url = apply_filters( 'fair_explorer_typo3_api_url', self::DEFAULT_API_URL );

		if ( ! is_string( $url ) || '' === trim( $url ) ) {
			return '';
		}

		return untrailingslashit( trim( $url ) );
	}

	/**
	 * Fetch TYPO3 extension data from the AspireCloud API.
	 *
	 * @param string $action API action ('query_extensions' or 'extension_information').
	 * @param array  $args   API arguments.
	 * @return object|\WP_Error API response object or error.
	 */
	public static function fetch( $action, $args ) {
		$base_url = self::get_api_url();

		if ( '' === $base_url ) {
			return new \WP_Error(
				'not_configured',
				__( 'TYPO3 extension data is not available. Please configure the AspireCloud API URL using the "fair_explorer_typo3_api_url" filter.', 'fair-explorer' )
			);
		}

		$endpoint = $base_url . '/packages/typo3-extension';

		if ( 'query_extensions' === $action ) {
			return self::fetch_archive( $endpoint, $args );
		}

		if ( 'extension_information' === $action ) {
			return self::fetch_single( $endpoint, $args );
		}

		return new \WP_Error(
			'unknown_action',
			sprintf(
				/* translators: %s: the API action name */
				__( 'Unknown TYPO3 API action: %s', 'fair-explorer' ),
				$action
			)
		);
	}

	/**
	 * Fetch archive listing of TYPO3 extensions.
	 *
	 * @param string $endpoint API endpoint URL.
	 * @param array  $args     Query arguments (search, page, per_page, browse).
	 * @return object|\WP_Error
	 */
	private static function fetch_archive( $endpoint, $args ) {
		$params = array_filter(
			[
				'q'        => $args['search'] ?? '',
				'page'     => (int) ( $args['page'] ?? 0 ),
				'per_page' => (int) ( $args['per_page'] ?? 0 ),
			]
		);

		$url      = add_query_arg( $params, $endpoint );
		$response = self::remote_get( $url );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$extensions = array_map( [ self::class, 'transform_package' ], $response['packages'] ?? [] );

		$result             = new \stdClass();
		$result->extensions = $extensions;
		$result->info       = [
			'results' => (int) ( $response['info']['total'] ?? 0 ),
		];

		return $result;
	}

	/**
	 * Fetch a single TYPO3 extension by slug.
	 *
	 * @param string $endpoint API endpoint URL.
	 * @param array  $args     Query arguments (slug, fields).
	 * @return object|\WP_Error
	 */
	private static function fetch_single( $endpoint, $args ) {
		$slug = $args['slug'] ?? '';

		if ( '' === $slug ) {
			return new \WP_Error(
				'missing_slug',
				__( 'Extension slug is required.', 'fair-explorer' )
			);
		}

		$url      = add_query_arg(
			[
				'q'        => $slug,
				'per_page' => 50,
			],
			$endpoint
		);
		$response = self::remote_get( $url );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$packages = $response['packages'] ?? [];

		foreach ( $packages as $package ) {
			if ( isset( $package['slug'] ) && $package['slug'] === $slug ) {
				return (object) self::transform_package( $package );
			}
		}

		return new \WP_Error(
			'not_found',
			sprintf(
				/* translators: %s: the extension slug */
				__( 'Extension "%s" not found.', 'fair-explorer' ),
				$slug
			)
		);
	}

	/**
	 * Perform an HTTP GET request and decode the JSON response.
	 *
	 * @param string $url Request URL.
	 * @return array|\WP_Error Decoded response body or error.
	 */
	private static function remote_get( $url ) {
		$response = wp_remote_get( $url, [ 'timeout' => 15 ] );

		if ( is_wp_error( $response ) ) {
			return $response;
		}

		$code = wp_remote_retrieve_response_code( $response );

		if ( 200 !== $code ) {
			return new \WP_Error(
				'api_error',
				sprintf(
					/* translators: %d: HTTP status code */
					__( 'AspireCloud API returned HTTP %d.', 'fair-explorer' ),
					$code
				)
			);
		}

		$body = json_decode( wp_remote_retrieve_body( $response ), true );

		if ( ! is_array( $body ) ) {
			return new \WP_Error(
				'invalid_response',
				__( 'AspireCloud API returned an invalid response.', 'fair-explorer' )
			);
		}

		return $body;
	}

	/**
	 * Transform an AspireCloud package into an ExtensionInfo-compatible array.
	 *
	 * @param array $package Raw package data from the API.
	 * @return array Transformed data matching ExtensionInfo fields.
	 */
	private static function transform_package( $package ) {
		$releases       = $package['releases'] ?? [];
		$latest_release = ! empty( $releases ) ? $releases[0] : [];
		$authors        = $package['authors'] ?? [];
		$slug           = $package['slug'] ?? '';

		// Build tags as slug => label from keywords.
		$tags = [];
		foreach ( $package['keywords'] ?? [] as $keyword ) {
			$tags[ sanitize_title( $keyword ) ] = $keyword;
		}

		// Collect unique TYPO3 compatibility values across all releases.
		$compatibility = [];
		foreach ( $releases as $release ) {
			$typo3_req = $release['requires']['typo3'] ?? '';
			if ( '' !== $typo3_req && ! in_array( $typo3_req, $compatibility, true ) ) {
				$compatibility[] = $typo3_req;
			}
		}

		$data = [
			'name'          => $package['name'] ?? '',
			'slug'          => $slug,
			'description'   => $package['description'] ?? '',
			'author'        => ! empty( $authors ) ? [ 'display_name' => $authors[0]['name'] ?? '' ] : null,
			'version'       => $latest_release['version'] ?? '',
			'download_link' => $latest_release['artifacts']['package'][0]['url'] ?? '',
			'requires_php'  => $latest_release['requires']['php'] ?? '',
			'tags'          => $tags,
			'sections'      => $package['sections'] ?? [],
			'extension_key' => $slug,
			'compatibility' => $compatibility,
		];

		if ( ! empty( $package['id'] ) ) {
			$data['_fair'] = (object) [ 'id' => $package['id'] ];
		}

		return $data;
	}
}
