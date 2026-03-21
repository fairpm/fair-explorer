<?php
$args['asset_info'] = $args['asset_info'] ?? $args['plugin_info'] ?? null;
$args['css_prefix'] = $args['css_prefix'] ?? 'plugin';
$args['asset_type']          = $args['asset_type'] ?? 'plugins';
$args['show_active_installs'] = true;
$args['show_cart']            = true;
require AE_DIR_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR . 'archive-card.php';
