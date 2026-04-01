<?php
$args['asset_info'] = $args['asset_info'] ?? $args['extension_info'] ?? null;
$args['css_prefix'] = $args['css_prefix'] ?? 'typo3-extension';
$args['asset_type']           = $args['asset_type'] ?? 'extensions';
$args['show_active_installs'] = false;
$args['show_fair_badge']      = false;

$asset_info = $args['asset_info'];
if ( $asset_info ) {
	$dl_items = '';

	$ext_key = $asset_info->get_extension_key();
	if ( $ext_key ) {
		$dl_items .= '<dt>' . esc_html__( 'Extension Key', 'fair-explorer' ) . '</dt><dd><code>' . esc_html( $ext_key ) . '</code></dd>';
	}

	$license = $asset_info->get_license();
	if ( $license ) {
		$dl_items .= '<dt>' . esc_html__( 'License', 'fair-explorer' ) . '</dt><dd>' . esc_html( $license ) . '</dd>';
	}

	$compatibility = $asset_info->get_compatibility();
	if ( ! empty( $compatibility ) ) {
		$dl_items .= '<dt>' . esc_html__( 'TYPO3', 'fair-explorer' ) . '</dt><dd>' . esc_html( implode( ', ', $compatibility ) ) . '</dd>';
	}

	$release_count = $asset_info->get_release_count();
	if ( $release_count > 0 ) {
		$dl_items .= '<dt>' . esc_html__( 'Releases', 'fair-explorer' ) . '</dt><dd>' . esc_html( $release_count ) . '</dd>';
	}

	if ( '' !== $dl_items ) {
		$args['pre_footer_html'] = '<dl class="' . esc_attr( $args['css_prefix'] ) . '-meta-dl">' . $dl_items . '</dl>';
	}
}
require AE_DIR_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR . 'archive-card.php';
