<?php
$extension_info     = $args['asset_info'] ?? $args['extension_info'] ?? null;
$args['asset_info'] = $extension_info;
$args['css_prefix'] = 'typo3-extension';
$args['asset_type'] = 'extensions';
$args['label']      = __( 'Extension', 'fair-explorer' );

$banner_url                       = $extension_info ? $extension_info->get_banners( 'high' ) : '';
$args['banner_url']               = $banner_url ? $banner_url : AE_DIR_URL . 'assets/images/default-banner.svg';
$args['show_preview']             = false;
$args['show_fair_badge']          = false;
$args['show_tags_sidebar']        = true;
$args['show_ratings']             = false;
$args['show_sections_accordion']  = false;
$args['pre_meta_html']            = '';
$args['meta_data']                = [];
$args['sidebar_sections']         = [];

if ( $extension_info ) {
	$args['meta_data'] = [
		'Version' => $extension_info->get_version(),
	];

	// Details section.
	$extension_key = $extension_info->get_extension_key();
	$ext_type      = $extension_info->get_type();
	$license       = $extension_info->get_license();
	$release_count = $extension_info->get_release_count();
	$details_html  = '<dl>';
	if ( $extension_key ) {
		$details_html .= '<dt>' . esc_html__( 'Slug', 'fair-explorer' ) . '</dt><dd><code>' . esc_html( $extension_key ) . '</code></dd>';
	}
	if ( $ext_type ) {
		$details_html .= '<dt>' . esc_html__( 'Type', 'fair-explorer' ) . '</dt><dd>' . esc_html( $ext_type ) . '</dd>';
	}
	if ( $license ) {
		$details_html .= '<dt>' . esc_html__( 'License', 'fair-explorer' ) . '</dt><dd>' . esc_html( $license ) . '</dd>';
	}
	if ( $release_count > 0 ) {
		$details_html .= '<dt>' . esc_html__( 'Releases', 'fair-explorer' ) . '</dt><dd>' . esc_html( $release_count ) . '</dd>';
	}
	$details_html .= '</dl>';
	$args['sidebar_sections'][] = [
		'title' => __( 'Details', 'fair-explorer' ),
		'html'  => $details_html,
		'class' => 'sidebar-details',
	];

	// Compatibility section.
	$compatibility = $extension_info->get_compatibility();
	if ( ! empty( $compatibility ) ) {
		$args['sidebar_sections'][] = [
			'title' => __( 'Compatibility', 'fair-explorer' ),
			'html'  => '<dl><dt>' . esc_html__( 'TYPO3', 'fair-explorer' ) . '</dt><dd>' . esc_html( implode( ', ', $compatibility ) ) . '</dd></dl>',
			'class' => 'sidebar-compatibility',
		];
	}

	// Authors section.
	$authors = $extension_info->get_authors();
	if ( ! empty( $authors ) ) {
		$authors_html = '<ul class="sidebar-authors">';
		foreach ( $authors as $author ) {
			$name = $author['name'] ?? '';
			if ( '' === $name ) {
				continue;
			}
			$initial       = mb_strtoupper( mb_substr( $name, 0, 1 ) );
			$authors_html .= '<li>';
			$authors_html .= '<span class="author-avatar" aria-hidden="true">' . esc_html( $initial ) . '</span>';
			$authors_html .= '<span class="author-name">' . esc_html( $name ) . '</span>';
			$authors_html .= '</li>';
		}
		$authors_html .= '</ul>';
		$args['sidebar_sections'][] = [
			'title' => __( 'Authors', 'fair-explorer' ),
			'html'  => $authors_html,
			'class' => 'sidebar-authors-section',
		];
	}

	// Releases table section.
	$releases = $extension_info->get_releases();
	if ( ! empty( $releases ) ) {
		ob_start();
		require __DIR__ . DIRECTORY_SEPARATOR . 'releases-table.php';
		$args['sidebar_sections'][] = [
			'title' => __( 'Releases', 'fair-explorer' ),
			'html'  => ob_get_clean(),
			'class' => 'sidebar-releases',
		];
	}
}

require AE_DIR_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR . 'single.php';
