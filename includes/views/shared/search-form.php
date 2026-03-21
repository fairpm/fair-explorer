<?php
$target_page_slug = $args['target_page_slug'] ?? '';
$search_keyword   = $args['search_keyword'] ?? '';
$css_prefix       = $args['css_prefix'] ?? '';
$search_label     = $args['search_label'] ?? '';
?>
<form method="get" action="<?php echo esc_url( get_bloginfo( 'url' ) . '/' . $target_page_slug ); ?>" class="<?php echo esc_attr( $css_prefix ); ?>-search-form" role="search" aria-label="<?php echo esc_attr( $search_label ); ?>">
	<label for="<?php echo esc_attr( $css_prefix ); ?>-search-keyword" class="screen-reader-text"><?php echo esc_html( $search_label ); ?></label>
	<input type="text" id="<?php echo esc_attr( $css_prefix ); ?>-search-keyword" name="keyword" placeholder="<?php echo esc_attr( $search_label ); ?>" value="<?php echo esc_attr( $search_keyword ); ?>" aria-label="<?php echo esc_attr( $search_label ); ?>" />
	<button type="submit" class="search-btn" aria-label="<?php echo esc_attr( sprintf( /* translators: %s: asset type label */ __( 'Submit %s search', 'fair-explorer' ), strtolower( $args['label'] ?? '' ) ) ); ?>">
		<span class="dashicons dashicons-search" aria-hidden="true"></span>
		<span class="screen-reader-text"><?php esc_html_e( 'Search', 'fair-explorer' ); ?></span>
	</button>
</form>
