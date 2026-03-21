<?php
$args['results']        = $args['results'] ?? $args['plugins_result'] ?? [];
$args['css_prefix']     = 'plugin';
$args['label']          = __( 'Plugin', 'fair-explorer' );
$args['label_plural']   = __( 'Plugins', 'fair-explorer' );
$args['model_class']    = 'FairExplorer\Model\PluginInfo';
$args['asset_type']     = 'plugins';
$args['asset_singular'] = 'plugin';
require AE_DIR_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR . 'archive-list.php';
