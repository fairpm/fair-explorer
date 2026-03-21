<?php
$target_page_slug  = $args['target_page_slug'] ?? '';
$extensions_result = $args['extensions_result'] ?? [];
$current_page      = $args['current_page'] ?? 1;
$total_results     = $args['total_results'] ?? 0;
$total_pages       = $args['total_pages'] ?? 1;
?>
<div class="archive-typo3-extension-card">
	<div class="typo3-extension-results-count">
		<p id="typo3-extension-results-count-text" aria-hidden="true">
			<?php
			printf(
				/* translators: %s: number of extensions found */
				esc_html( _n( '%s Extension Found.', '%s Extensions Found.', $total_results, 'fair-explorer' ) ),
				esc_html( $total_results )
			);
			?>
		</p>
		<span class="screen-reader-text" aria-live="polite" aria-atomic="true" id="typo3-extension-results-count-sr">
			<?php
			printf(
				esc_html(
					/* translators: %s: number of extensions in the results list */
					_n(
						'%s extension found in the results list below.',
						'%s extensions found in the results list below.',
						$total_results,
						'fair-explorer'
					)
				),
				esc_html( $total_results )
			);
			?>
		</span>
	</div>
	<ul class="typo3-extension-results" role="list">
		<?php
		foreach ( $extensions_result as $extension_result ) {
			$extension_info = new \FairExplorer\Model\ExtensionInfo( $extension_result );
			\FairExplorer\Controller\Utilities::include_file(
				'extensions' . DIRECTORY_SEPARATOR . 'archive' . DIRECTORY_SEPARATOR . 'extension.php',
				[
					'target_page_slug' => $target_page_slug,
					'extension_info'   => $extension_info,
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
