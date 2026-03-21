<?php
$args['results']        = $args['results'] ?? $args['extensions_result'] ?? [];
$args['css_prefix']     = 'typo3-extension';
$args['label']          = __( 'Extension', 'fair-explorer' );
$args['label_plural']   = __( 'Extensions', 'fair-explorer' );
$args['model_class']    = 'FairExplorer\Model\ExtensionInfo';
$args['asset_type']     = 'extensions';
$args['asset_singular'] = 'extension';
require AE_DIR_PATH . DIRECTORY_SEPARATOR . 'includes' . DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . 'shared' . DIRECTORY_SEPARATOR . 'archive-list.php';
