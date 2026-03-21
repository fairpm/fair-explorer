<?php

/**
 * @var FairExplorer\Model\ExtensionInfo extension_info.
 */
$extension_info = $args['extension_info'] ?? [];

$target_page_slug = $args['target_page_slug'] ?? '';
$extension_url    = home_url( '/' . $target_page_slug . '/' . $extension_info->get_slug() . '/' );

$extension_icon = $extension_info->get_best_icon();
if ( empty( $extension_icon ) ) {
	$extension_icon = AE_DIR_URL . 'assets/images/default-icon.svg';
}
?>
<li class="typo3-extension-card">
	<header class="entry-header">
		<div class="entry-thumbnail">
			<img class="typo3-extension-icon" src="<?php echo esc_url( $extension_icon ); ?>" alt="<?php echo esc_attr( $extension_info->get_name() ); ?> <?php esc_attr_e( 'icon', 'fair-explorer' ); ?>">
		</div>
		<div class="entry-title">
			<h2 class="typo3-extension-title">
				<a href="<?php echo esc_url( $extension_url ); ?>">
					<?php echo esc_html( $extension_info->get_name() ); ?>
				</a>
			</h2>
			<p class="typo3-extension-author">
				<span class="screen-reader-text"><?php esc_html_e( 'Author:', 'fair-explorer' ); ?> </span>
				<?php esc_html_e( 'by', 'fair-explorer' ); ?>
				<span>
					<?php echo esc_html( $extension_info->get_author( 'display_name' ) ); ?>
				</span>
			</p>
			<p class="typo3-extension-version">
				<span><?php esc_html_e( 'version', 'fair-explorer' ); ?></span> <?php echo esc_html( $extension_info->get_version() ); ?>
			</p>
		</div>
	</header>
	<div class="entry-excerpt">
		<p>
			<?php echo esc_html( wp_trim_words( $extension_info->get_short_description(), 30 ) ); ?>
		</p>
	</div>
	<footer>
		<?php
		$extension_key = $extension_info->get_extension_key();
		if ( $extension_key ) :
			?>
			<p class="typo3-extension-key">
				<strong><?php esc_html_e( 'Extension Key:', 'fair-explorer' ); ?></strong>
				<code><?php echo esc_html( $extension_key ); ?></code>
			</p>
		<?php endif; ?>
		<p class="active-installs">
			<?php
			$active_installs = $extension_info->get_active_installs();
			if ( is_null( $active_installs ) ) {
				?>
				<span>
					<?php esc_html_e( 'Installation Count not Available', 'fair-explorer' ); ?>
				</span>
				<?php
			} else {
				?>
				<span>
					<?php echo esc_html( $extension_info->get_active_installs() ); ?> <?php esc_html_e( 'Active installations', 'fair-explorer' ); ?>
				</span>
				<?php
			}
			?>
		</p>
		<p class="entry-download">
			<a href="<?php echo esc_url( $extension_info->get_download_link() ); ?>"
				class="button button-primary"
				download
				rel="noopener noreferrer"
				aria-label="<?php esc_attr_e( 'Download', 'fair-explorer' ); ?> <?php echo esc_attr( $extension_info->get_name() ); ?> <?php esc_attr_e( 'extension', 'fair-explorer' ); ?>">
				<span class="dashicons dashicons-download" aria-hidden="true"></span>
				<?php esc_html_e( 'Download', 'fair-explorer' ); ?>
			</a>
		</p>
		<div class="entry-tags">
			<ul class="typo3-extension-tags">
				<?php
				$tags = $extension_info->get_tags();
				$tags = array_slice( $tags, 0, 5 );
				foreach ( $tags as $extension_tag ) {
					echo '<li class="typo3-extension-tag"><span class="screen-reader-text">' . esc_html__( 'Tag:', 'fair-explorer' ) . ' </span><span>' . esc_html( $extension_tag ) . '</span></li>';
				}
				?>
			</ul>
		</div>
	</footer>
</li>
