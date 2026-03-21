<?php
$plugin_info        = $args['asset_info'] ?? $args['plugin_info'] ?? null;
$args['asset_info'] = $plugin_info;
$args['css_prefix'] = 'plugin';
$args['asset_type'] = 'plugins';
$args['label']      = __( 'Plugin', 'fair-explorer' );

$banner_url                = $plugin_info ? $plugin_info->get_banners( 'high' ) : '';
$args['banner_url']        = $banner_url ? $banner_url : AE_DIR_URL . 'assets/images/default-banner.svg';
$args['show_preview']      = false;
$args['show_fair_badge']   = $plugin_info ? $plugin_info->is_fair_plugin() : false;
$args['show_tags_sidebar'] = false;

$args['pre_meta_html'] = '';
if ( $plugin_info && $plugin_info->is_fair_plugin() ) {
	$fair_data             = $plugin_info->get_fair_data();
	$args['pre_meta_html'] = '<li class="plugin-meta-item fair-plugin"><strong>Plugin DID:</strong> <code>' . esc_html( $fair_data->id ) . '</code></li>';
}

$args['meta_data'] = [];
if ( $plugin_info ) {
	$args['meta_data'] = [
		'Version'         => $plugin_info->get_version(),
		'Business model'  => $plugin_info->get_business_model(),
		'Active installs' => $plugin_info->get_active_installs(),
		'Last updated'    => $plugin_info->get_last_updated(),
		'Requires'        => $plugin_info->get_requires(),
		'Tested'          => $plugin_info->get_tested(),
	];
}

require AE_DIR_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR . 'single.php';
