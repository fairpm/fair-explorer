<?php
$theme_info         = $args['asset_info'] ?? $args['theme_info'] ?? null;
$args['asset_info'] = $theme_info;
$args['css_prefix'] = 'theme';
$args['asset_type'] = 'themes';
$args['label']      = __( 'Theme', 'fair-explorer' );

$screenshot                = $theme_info ? $theme_info->get_screenshot_url() : '';
$args['banner_url']        = $screenshot ? $screenshot : AE_DIR_URL . 'assets/images/default-banner.svg';
$args['show_preview']      = true;
$args['show_fair_badge']   = false;
$args['show_tags_sidebar'] = true;
$args['pre_meta_html']     = '';

$args['meta_data'] = [];
if ( $theme_info ) {
	$args['meta_data'] = [
		'Version'         => $theme_info->get_version(),
		'Active Installs' => $theme_info->get_active_installs(),
		'Last Updated'    => $theme_info->get_last_updated(),
		'Requires WP'     => $theme_info->get_requires(),
		'Tested'          => $theme_info->get_tested(),
		'Requires PHP'    => $theme_info->get_requires_php(),
	];
}

require AE_DIR_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR . 'single.php';
