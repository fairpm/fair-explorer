<?php

/**
 * @var FairExplorer\Model\PluginInfo plugin_info.
 */
$plugin_info = $args['plugin_info'] ?? [];

$target_page_slug = $args['target_page_slug'] ?? '';
$plugin_url       = home_url( '/' . $target_page_slug . '/' . $plugin_info->get_slug() . '/' );

$plugin_icon = $plugin_info->get_best_icon();
if ( empty( $plugin_icon ) ) {
	$plugin_icon = AE_DIR_URL . 'assets/images/default-icon.svg';
}

$fair_data = $plugin_info->get_fair_data();
if ( $plugin_info->is_fair_plugin() ) {
	$plugin_did = esc_attr( $fair_data->id );
}
?>
<li class="plugin-card <?php echo ( $plugin_info->is_fair_plugin() ) ? 'fair' : ''; ?>">
	<header class="entry-header">
		<div class="entry-thumbnail">
			<img class="plugin-icon" src="<?php echo esc_url( $plugin_icon ); ?>" alt="<?php echo esc_attr( $plugin_info->get_name() ); ?> <?php esc_attr_e( 'icon', 'fair-explorer' ); ?>">
		</div>
		<div class="entry-title">
			<h2 class="plugin-title">
				<a href="<?php echo esc_url( $plugin_url ); ?>">
					<?php echo esc_html( $plugin_info->get_name() ); ?>
				</a>
			</h2>
			<p class="plugin-author">
				<span class="screen-reader-text"><?php esc_html_e( 'Author:', 'fair-explorer' ); ?> </span>
				<?php esc_html_e( 'by', 'fair-explorer' ); ?>
				<span>
					<?php echo esc_html( $plugin_info->get_author() ); ?>
				</span>
			</p>
			<p class="plugin-version">
				<span><?php esc_html_e( 'version', 'fair-explorer' ); ?></span> <?php echo esc_html( $plugin_info->get_version() ); ?>
			</p>
		</div>
	</header>
	<div class="entry-excerpt">
		<p>
			<?php echo esc_html( wp_trim_words( $plugin_info->get_short_description(), 30 ) ); ?>
		</p>
	</div>
	<footer>
		<p class="active-installs">
			<?php
			$active_installs = $plugin_info->get_active_installs();
			if ( is_null( $active_installs ) ) {
				?>
				<span>
					<?php esc_html_e( 'Installation Count not Available', 'fair-explorer' ); ?>
				</span>
				<?php
			} else {
				?>
				<span>
					<?php echo esc_html( $plugin_info->get_active_installs() ); ?> <?php esc_html_e( 'Active installations', 'fair-explorer' ); ?>
				</span>
				<?php
			}
			?>
		</p>
		<p class="entry-add-to-cart">
			<button class="button button-secondary" data-slug="<?php echo esc_attr( $plugin_info->get_slug() ); ?>">
				<span class="dashicons dashicons-cart" aria-hidden="true"></span>
				<span class="screen-reader-text"><?php esc_attr_e( 'Add to cart', 'fair-explorer' ); ?> <?php echo esc_attr( $plugin_info->get_name() ); ?> <?php esc_attr_e( 'plugin', 'fair-explorer' ); ?></span>
			</button>
		</p>
		<p class="entry-download">
			<a href="<?php echo esc_url( $plugin_info->get_download_link() ); ?>"
				class="button button-primary"
				download
				rel="noopener noreferrer"
				aria-label="<?php esc_attr_e( 'Download', 'fair-explorer' ); ?> <?php echo esc_attr( $plugin_info->get_name() ); ?> <?php esc_attr_e( 'plugin', 'fair-explorer' ); ?>">
				<span class="dashicons dashicons-download" aria-hidden="true"></span>
				<?php esc_html_e( 'Download', 'fair-explorer' ); ?>
			</a>
		</p>
		<div class="entry-tags">
			<ul class="plugin-tags">
				<?php
				$tags = $plugin_info->get_tags();
				$tags = array_slice( $tags, 0, 5 );
				foreach ( $tags as $plugin_tag ) {
					echo '<li class="plugin-tag"><span class="screen-reader-text">' . esc_html__( 'Tag:', 'fair-explorer' ) . ' </span><span>' . esc_html( $plugin_tag ) . '</span></li>';
				}
				?>
			</ul>
		</div>
		<?php if ( $plugin_info->is_fair_plugin() ) : ?>
			<div class="fair-badge">
				<p><?php esc_html_e( 'This plugin is available via FAIR repository.', 'fair-explorer' ); ?></p>
			</div>
		<?php endif; ?>
	</footer>
</li>
