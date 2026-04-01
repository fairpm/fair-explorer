<?php
/**
 * Releases table partial for TYPO3 extension single page.
 *
 * Expects $releases to be set by the including file.
 * Rendered inside a sidebar-section wrapper, so no outer div or heading needed.
 */

if ( empty( $releases ) ) {
	return;
}
?>
<table class="typo3-releases-table">
	<thead>
		<tr>
			<th><?php esc_html_e( 'Version', 'fair-explorer' ); ?></th>
			<th><?php esc_html_e( 'TYPO3', 'fair-explorer' ); ?></th>
			<th><?php esc_html_e( 'Download', 'fair-explorer' ); ?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ( $releases as $release ) : ?>
			<?php
			$version      = $release['version'] ?? '';
			$typo3_compat = $release['requires']['env:typo3'] ?? '';
			$download_url = $release['artifacts']['package'][0]['url'] ?? '';
			?>
			<tr>
				<td><?php echo esc_html( $version ); ?></td>
				<td><?php echo esc_html( $typo3_compat ); ?></td>
				<td>
					<?php if ( $download_url ) : ?>
						<a href="<?php echo esc_url( $download_url ); ?>" download rel="noopener noreferrer">
							<span class="dashicons dashicons-download" aria-hidden="true"></span>
							<span class="screen-reader-text"><?php echo esc_html( sprintf( /* translators: %s: version number */ __( 'Download version %s', 'fair-explorer' ), $version ) ); ?></span>
						</a>
					<?php endif; ?>
				</td>
			</tr>
		<?php endforeach; ?>
	</tbody>
</table>
