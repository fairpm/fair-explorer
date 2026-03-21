<?php
$args['results']        = $args['results'] ?? $args['themes_result'] ?? [];
$args['css_prefix']     = 'theme';
$args['label']          = __( 'Theme', 'fair-explorer' );
$args['label_plural']   = __( 'Themes', 'fair-explorer' );
$args['model_class']    = 'FairExplorer\Model\ThemeInfo';
$args['asset_type']     = 'themes';
$args['asset_singular'] = 'theme';
require AE_DIR_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR . 'archive-list.php';
