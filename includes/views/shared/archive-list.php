<?php
$target_page_slug = $args['target_page_slug'] ?? '';
$results          = $args['results'] ?? [];
$current_page     = $args['current_page'] ?? 1;
$total_results    = $args['total_results'] ?? 0;
$total_pages      = $args['total_pages'] ?? 1;
$css_prefix       = $args['css_prefix'] ?? '';
$label            = $args['label'] ?? '';
$label_plural     = $args['label_plural'] ?? '';
$model_class      = $args['model_class'] ?? '';
$asset_type       = $args['asset_type'] ?? '';
$asset_singular   = $args['asset_singular'] ?? '';
?>
<div class="archive-<?php echo esc_attr( $css_prefix ); ?>-card">
	<div class="<?php echo esc_attr( $css_prefix ); ?>-results-count">
		<p id="<?php echo esc_attr( $css_prefix ); ?>-results-count-text" aria-hidden="true">
			<?php
			printf(
				/* translators: %1$s: number of results, %2$s: asset type label */
				esc_html( _n( '%1$s %2$s Found.', '%1$s %2$s Found.', $total_results, 'fair-explorer' ) ),
				esc_html( $total_results ),
				esc_html( 1 === $total_results ? $label : $label_plural )
			);
			?>
		</p>
		<span class="screen-reader-text" aria-live="polite" aria-atomic="true" id="<?php echo esc_attr( $css_prefix ); ?>-results-count-sr">
			<?php
			printf(
				esc_html(
					/* translators: %1$s: number of results, %2$s: asset type label */
					_n(
						'%1$s %2$s found in the results list below.',
						'%1$s %2$s found in the results list below.',
						$total_results,
						'fair-explorer'
					)
				),
				esc_html( $total_results ),
				esc_html( 1 === $total_results ? strtolower( $label ) : strtolower( $label_plural ) )
			);
			?>
		</span>
	</div>
	<ul class="<?php echo esc_attr( $css_prefix ); ?>-results" role="list">
		<?php
		foreach ( $results as $result ) {
			$asset_info = new $model_class( $result );
			\FairExplorer\Controller\Utilities::include_file(
				$asset_type . DIRECTORY_SEPARATOR . 'archive' . DIRECTORY_SEPARATOR . $asset_singular . '.php',
				[
					'target_page_slug'        => $target_page_slug,
					$asset_singular . '_info' => $asset_info,
					'asset_info'              => $asset_info,
					'css_prefix'              => $css_prefix,
					'asset_type'              => $asset_type,
				]
			);
		}
		?>
	</ul>
	<div class="pagination-wrapper">
		<?php
		if ( 1 < $total_pages ) {
			$big = 999999999;
			echo wp_kses_post(
				paginate_links(
					[
						'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
						'format'    => '?paged=%#%',
						'current'   => $current_page,
						'total'     => $total_pages,
						'prev_next' => false,
						'type'      => 'list',
					]
				)
			);
		}
		?>
	</div>
</div>
