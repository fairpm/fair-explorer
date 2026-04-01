<?php
$asset_info            = $args['asset_info'] ?? null;
$target_page_slug      = $args['target_page_slug'] ?? '';
$css_prefix            = $args['css_prefix'] ?? '';
$asset_type            = $args['asset_type'] ?? '';
$show_active_installs  = $args['show_active_installs'] ?? false;
$show_cart             = $args['show_cart'] ?? false;
$show_preview          = $args['show_preview'] ?? false;
$show_fair_badge       = $args['show_fair_badge'] ?? true;
$preview_url           = $args['preview_url'] ?? '';
$pre_footer_html       = $args['pre_footer_html'] ?? '';

if ( ! $asset_info ) {
	return;
}

$asset_url = home_url( '/' . $target_page_slug . '/' . $asset_info->get_slug() . '/' );

$has_icon       = method_exists( $asset_info, 'get_best_icon' );
$has_screenshot = method_exists( $asset_info, 'get_screenshot_url' );

// Determine thumbnail: icon-based types vs screenshot-based types
$show_icon       = $has_icon && ! $has_screenshot;
$show_screenshot = $has_screenshot;

if ( $show_icon ) {
	$icon_url = $asset_info->get_best_icon();
	if ( empty( $icon_url ) ) {
		$icon_url = AE_DIR_URL . 'assets/images/default-icon.svg';
	}
}

if ( $show_screenshot ) {
	$screenshot_url = $asset_info->get_screenshot_url();
	if ( empty( $screenshot_url ) ) {
		$screenshot_url = AE_DIR_URL . 'assets/images/default-banner.svg';
	}
}

$is_fair   = $asset_info->is_fair_plugin();
$fair_data = $is_fair ? $asset_info->get_fair_data() : false;

$author_display = $asset_info->get_author_display_name();
?>
<li class="<?php echo esc_attr( $css_prefix ); ?>-card <?php echo $is_fair ? 'fair' : ''; ?>">
	<?php if ( $show_screenshot ) : ?>
		<div class="<?php echo esc_attr( $css_prefix ); ?>-banner">
			<a href="<?php echo esc_url( $asset_url ); ?>">
				<img src="<?php echo esc_url( $screenshot_url ); ?>"
					alt="<?php echo esc_attr( $asset_info->get_name() ); ?> <?php esc_attr_e( 'theme screenshot', 'fair-explorer' ); ?>"
					loading="lazy" />
			</a>
		</div>
	<?php endif; ?>
	<header class="entry-header">
		<?php if ( $show_icon ) : ?>
			<div class="entry-thumbnail">
				<img class="<?php echo esc_attr( $css_prefix ); ?>-icon" src="<?php echo esc_url( $icon_url ); ?>" alt="<?php echo esc_attr( $asset_info->get_name() ); ?> <?php esc_attr_e( 'icon', 'fair-explorer' ); ?>">
			</div>
		<?php endif; ?>
		<div class="entry-title">
			<h2 class="<?php echo esc_attr( $css_prefix ); ?>-title">
				<a href="<?php echo esc_url( $asset_url ); ?>">
					<?php echo esc_html( $asset_info->get_name() ); ?>
				</a>
			</h2>
			<p class="<?php echo esc_attr( $css_prefix ); ?>-author">
				<span class="screen-reader-text"><?php esc_html_e( 'Author:', 'fair-explorer' ); ?> </span>
				<?php esc_html_e( 'by', 'fair-explorer' ); ?>
				<span>
					<?php echo esc_html( $author_display ); ?>
				</span>
			</p>
			<p class="<?php echo esc_attr( $css_prefix ); ?>-version">
				<span><?php esc_html_e( 'version', 'fair-explorer' ); ?></span> <?php echo esc_html( $asset_info->get_version() ); ?>
			</p>
		</div>
	</header>
	<div class="entry-excerpt">
		<p>
			<?php echo esc_html( wp_trim_words( $has_screenshot ? $asset_info->get_description() : ( $asset_info->get_short_description() ?? $asset_info->get_description() ), 30 ) ); ?>
		</p>
	</div>
	<footer>
		<?php
		// Optional pre-footer HTML (e.g. extension key)
		if ( $pre_footer_html ) {
			echo $pre_footer_html; // Already escaped by the wrapper template.
		}

		// Active installs
		if ( $show_active_installs ) :
			?>
			<p class="active-installs">
				<?php
				$active_installs = $asset_info->get_active_installs();
				if ( is_null( $active_installs ) ) {
					?>
					<span>
						<?php esc_html_e( 'Installation Count not Available', 'fair-explorer' ); ?>
					</span>
					<?php
				} else {
					?>
					<span>
						<?php echo esc_html( $asset_info->get_active_installs() ); ?> <?php esc_html_e( 'Active installations', 'fair-explorer' ); ?>
					</span>
					<?php
				}
				?>
			</p>
			<?php
		else :
			// Empty spacer to keep card footer layout consistent with types that show active installs.
			?>
			<div class="active-installs"></div>
			<?php
		endif;

		// Add to cart
		if ( $show_cart ) :
			?>
			<p class="entry-add-to-cart">
				<button class="button button-secondary" data-slug="<?php echo esc_attr( $asset_info->get_slug() ); ?>">
					<span class="dashicons dashicons-cart" aria-hidden="true"></span>
					<span class="screen-reader-text"><?php esc_attr_e( 'Add to cart', 'fair-explorer' ); ?> <?php echo esc_attr( $asset_info->get_name() ); ?> <?php esc_attr_e( 'plugin', 'fair-explorer' ); ?></span>
				</button>
			</p>
		<?php endif; ?>

		<?php
		// Preview
		if ( $show_preview ) :
			?>
			<p class="entry-preview">
				<a href="<?php echo esc_url( $preview_url ); ?>" class="button button-primary" target="_blank" rel="noopener noreferrer">
					<span class="dashicons dashicons-visibility"></span> <?php esc_html_e( 'Preview', 'fair-explorer' ); ?>
				</a>
			</p>
		<?php endif; ?>

		<p class="entry-download">
			<a href="<?php echo esc_url( $asset_info->get_download_link() ); ?>"
				class="button button-primary"
				download
				rel="noopener noreferrer"
				aria-label="<?php esc_attr_e( 'Download', 'fair-explorer' ); ?> <?php echo esc_attr( $asset_info->get_name() ); ?>">
				<span class="dashicons dashicons-download" aria-hidden="true"></span>
				<?php esc_html_e( 'Download', 'fair-explorer' ); ?>
			</a>
		</p>
		<div class="entry-tags">
			<ul class="<?php echo esc_attr( $css_prefix ); ?>-tags">
				<?php
				$tags = $asset_info->get_tags();
				$tags = array_slice( $tags, 0, 5 );
				foreach ( $tags as $asset_tag ) {
					echo '<li class="' . esc_attr( $css_prefix ) . '-tag"><span class="screen-reader-text">' . esc_html__( 'Tag:', 'fair-explorer' ) . ' </span><span>' . esc_html( $asset_tag ) . '</span></li>';
				}
				?>
			</ul>
		</div>
		<?php if ( $is_fair && $show_fair_badge ) : ?>
			<div class="fair-badge">
				<p><?php esc_html_e( 'This plugin is available via FAIR repository.', 'fair-explorer' ); ?></p>
			</div>
		<?php endif; ?>
	</footer>
</li>
