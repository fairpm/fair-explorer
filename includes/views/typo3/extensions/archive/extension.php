<?php
$args['asset_info'] = $args['asset_info'] ?? $args['extension_info'] ?? null;
$args['css_prefix'] = $args['css_prefix'] ?? 'typo3-extension';
$args['asset_type']           = $args['asset_type'] ?? 'extensions';
$args['show_active_installs'] = false;
$args['show_fair_badge']      = false;

$asset_info = $args['asset_info'] ?? $args['extension_info'] ?? null;
if ( $asset_info ) {
	$ext_key = $asset_info->get_extension_key();
	if ( $ext_key ) {
		$args['pre_footer_html'] = '<p class="' . esc_attr( $args['css_prefix'] ?? '' ) . '-key"><strong>' . esc_html__( 'Extension Key:', 'fair-explorer' ) . '</strong> <code>' . esc_html( $ext_key ) . '</code></p>';
	}
}
require AE_DIR_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR . 'archive-card.php';
