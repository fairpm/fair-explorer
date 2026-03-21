<?php
$args['asset_info'] = $args['asset_info'] ?? $args['theme_info'] ?? null;
$args['css_prefix'] = $args['css_prefix'] ?? 'theme';
$args['asset_type']    = $args['asset_type'] ?? 'themes';
$args['show_preview']  = true;

$asset_info = $args['asset_info'] ?? $args['theme_info'] ?? null;
if ( $asset_info && $asset_info->get_slug() ) {
	$args['preview_url'] = FairExplorer\Controller\WordPress\Playground::get_playground_url( [ 'theme' => $asset_info->get_download_link() ] );
}
require AE_DIR_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR . 'archive-card.php';
