<?php
$extension_info     = $args['asset_info'] ?? $args['extension_info'] ?? null;
$args['asset_info'] = $extension_info;
$args['css_prefix'] = 'typo3-extension';
$args['asset_type'] = 'extensions';
$args['label']      = __( 'Extension', 'fair-explorer' );

$banner_url                = $extension_info ? $extension_info->get_banners( 'high' ) : '';
$args['banner_url']        = $banner_url ? $banner_url : AE_DIR_URL . 'assets/images/default-banner.svg';
$args['show_preview']      = false;
$args['show_fair_badge']   = false;
$args['show_tags_sidebar'] = false;

$args['pre_meta_html'] = '';
if ( $extension_info ) {
	$extension_key = $extension_info->get_extension_key();
	if ( $extension_key ) {
		$args['pre_meta_html'] = '<li class="typo3-extension-meta-item"><strong>' . esc_html__( 'Extension Key', 'fair-explorer' ) . ':</strong> <code>' . esc_html( $extension_key ) . '</code></li>';
	}
}

$args['meta_data'] = [];
if ( $extension_info ) {
	$args['meta_data'] = [
		'Version'         => $extension_info->get_version(),
		'Active Installs' => $extension_info->get_active_installs(),
		'Last Updated'    => $extension_info->get_last_updated(),
	];

	$compatibility = $extension_info->get_compatibility();
	if ( ! empty( $compatibility ) ) {
		$args['meta_data']['TYPO3 Compatibility'] = implode( ', ', $compatibility );
	}
}

require AE_DIR_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR . 'single.php';
