<?php
$asset_info        = $args['asset_info'] ?? null;
$css_prefix        = $args['css_prefix'] ?? '';
$asset_type        = $args['asset_type'] ?? '';
$label             = $args['label'] ?? '';
$banner_url        = $args['banner_url'] ?? '';
$show_preview      = $args['show_preview'] ?? false;
$show_fair_badge   = $args['show_fair_badge'] ?? false;
$show_tags_sidebar = $args['show_tags_sidebar'] ?? false;
$pre_meta_html      = $args['pre_meta_html'] ?? '';
$meta_data          = $args['meta_data'] ?? [];
$sidebar_sections   = $args['sidebar_sections'] ?? [];
$post_article_html  = $args['post_article_html'] ?? '';
$show_ratings            = $args['show_ratings'] ?? true;
$show_sections_accordion = $args['show_sections_accordion'] ?? true;

if ( ! $asset_info ) {
	return;
}

$sections    = $asset_info->get_sections();
$description = '';
if ( isset( $sections['description'] ) ) {
	$description = $sections['description'];
} elseif ( isset( $sections['changelog'] ) ) {
	$description = $sections['changelog'];
} elseif ( isset( $sections['installation'] ) ) {
	$description = $sections['installation'];
} elseif ( isset( $sections['faq'] ) ) {
	$description = $sections['faq'];
} else {
	$description = $asset_info->get_description();
}

$author_display = $asset_info->get_author_display_name();
?>
<div class="single-<?php echo esc_attr( $css_prefix ); ?>-card">
	<banner class="entry-banner">
		<img class="<?php echo esc_attr( $css_prefix ); ?>-banner" src="<?php echo esc_url( $banner_url ); ?>" alt="<?php echo esc_attr( $label ); ?> Banner" fetchpriority="high">
	</banner>
	<header class="entry-header">
		<div class="entry-title">
			<h2 class="<?php echo esc_attr( $css_prefix ); ?>-title"><?php echo esc_html( $asset_info->get_name() ); ?></h2>
			<p class="<?php echo esc_attr( $css_prefix ); ?>-author">by <?php echo esc_html( $author_display ); ?></p>
			<?php if ( $show_fair_badge ) : ?>
				<p class="<?php echo esc_attr( $css_prefix ); ?>-fair"><?php esc_html_e( 'This plugin is available via FAIR repository.', 'fair-explorer' ); ?></p>
			<?php endif; ?>
		</div>
		<?php if ( $show_preview ) : ?>
			<div class="entry-preview">
				<?php
				$theme_slug  = $asset_info->get_slug();
				$preview_url = '';
				if ( $theme_slug ) {
					$zip_url     = $asset_info->get_download_link();
					$preview_url = FairExplorer\Controller\WordPress\Playground::get_playground_url( [ 'theme' => $zip_url ] );
				}
				?>
				<a href="<?php echo esc_url( $preview_url ); ?>" class="button button-primary" target="_blank" rel="noopener noreferrer">
					<span class="dashicons dashicons-visibility"></span> <?php esc_html_e( 'Preview', 'fair-explorer' ); ?>
				</a>
			</div>
		<?php endif; ?>
		<div class="entry-download">
			<a href="<?php echo esc_url( $asset_info->get_download_link() ); ?>" class="button button-primary" download rel="noopener noreferrer"><span class="dashicons dashicons-download"></span> <?php esc_html_e( 'Download', 'fair-explorer' ); ?></a>
		</div>
	</header>
	<div class="entry-main">
		<article>
			<?php
			if ( is_array( $sections ) && count( $sections ) > 0 ) {
				$priority_map = array_flip( [
					'description',
					'installation',
					'screenshots',
					'faq',
					'support',
					'reviews',
					'changelog',
					'other_notes',
				] );
				uksort(
					$sections,
					function ( $a, $b ) use ( $priority_map ) {
						$pos_a = $priority_map[ $a ] ?? PHP_INT_MAX;
						$pos_b = $priority_map[ $b ] ?? PHP_INT_MAX;
						return $pos_a - $pos_b;
					}
				);

				if ( $show_sections_accordion ) {
					$is_first = true;
					foreach ( $sections as $section => $content ) {
						echo '<details class="section-item" id="section-item-' . esc_attr( $section ) . '" ' . esc_attr( ( $is_first ) ? 'open' : '' ) . '>';
							echo '<summary role="button" aria-expanded="' . esc_attr( ( $is_first ) ? 'true' : 'false' ) . '">' . esc_html( ucfirst( $section ) ) . '</summary>';
							echo '<div class="details-content" id="details-content-' . esc_attr( $section ) . '">' . wp_kses_post( $content ) . '</div>';
						echo '</details>';
						if ( $is_first ) {
							$is_first = false;
						}
					}
				} else {
					foreach ( $sections as $section => $content ) {
						echo '<div class="section-item" id="section-item-' . esc_attr( $section ) . '">';
						echo '<div class="details-content" id="details-content-' . esc_attr( $section ) . '">' . wp_kses_post( $content ) . '</div>';
						echo '</div>';
					}
				}
			}
			?>
		</article>
		<?php
		if ( '' !== $post_article_html ) {
			echo wp_kses_post( $post_article_html );
		}
		?>
		<aside aria-label="<?php echo esc_attr( sprintf( /* translators: %s: asset type label */ __( '%s Metadata', 'fair-explorer' ), $label ) ); ?>">
			<ul>
				<?php
				// Pre-meta HTML (FAIR DID, extension key, etc.)
				if ( '' !== $pre_meta_html ) {
					echo wp_kses_post( $pre_meta_html );
				}

				foreach ( $meta_data as $key => $value ) {
					if ( empty( $value ) ) {
						continue;
					}
					if ( is_array( $value ) ) {
						$value = implode( ', ', $value );
					}
					echo '<li class="' . esc_attr( $css_prefix ) . '-meta-item"><strong>' . esc_html( $key ) . ':</strong> ' . esc_html( $value ) . '</li>';
				}
				?>
			</ul>
			<?php
			foreach ( $sidebar_sections as $sidebar_section ) {
				$section_class = ! empty( $sidebar_section['class'] ) ? ' ' . esc_attr( $sidebar_section['class'] ) : '';
				echo '<div class="sidebar-section' . $section_class . '">';
				if ( ! empty( $sidebar_section['title'] ) ) {
					echo '<h3>' . esc_html( $sidebar_section['title'] ) . '</h3>';
				}
				echo wp_kses_post( $sidebar_section['html'] ?? '' );
				echo '</div>';
			}
			?>
			<?php if ( $show_ratings ) : ?>
			<div class="ratings">
				<?php
				$total   = 0;
				$sum     = 0;
				$ratings = $asset_info->get_ratings();
				foreach ( $ratings as $star => $num ) {
					$total += (int) $num;
					$sum   += (int) $num * (int) $star;
				}
				$average = $total > 0 ? round( $sum / $total, 1 ) : 0;
				?>
				<div class="rating-summary">
					<strong><span class="screen-reader-text"><?php echo esc_html__( 'Average rating:', 'fair-explorer' ); ?></span><?php echo esc_html( $average ); ?> <?php echo esc_html__( 'out of 5 stars.', 'fair-explorer' ); ?></strong>
				</div>
				<ul class="ratings-list">
					<?php
					for ( $i = 5; $i >= 1; $i-- ) {
						$count = isset( $ratings[ $i ] ) ? (int) $ratings[ $i ] : 0;
						echo '<li class="rating-row">';
						for ( $j = 1; $j <= 5; $j++ ) {
							echo '<span class="dashicons dashicons-star' . ( $j <= $i ? '-filled' : '-empty' ) . '" aria-hidden="true"></span>';
						}
						echo '<span class="rating-bar"><span class="rating-bar-inner" style="width:' . ( $total > 0 ? esc_attr( round( ( $count / $total ) * 100 ) ) : 0 ) . '%"></span></span>';
						echo '<span class="rating-absolute"><span class="screen-reader-text">' . esc_html__( 'Number of ratings:', 'fair-explorer' ) . ' </span>' . esc_html( $count ) . ' ' . esc_html__( 'ratings', 'fair-explorer' ) . '</span>';
						echo '</li>';
					}
					?>
				</ul>
			</div>
			<?php endif; ?>
			<?php if ( $show_tags_sidebar ) : ?>
				<div class="entry-tags">
					<ul class="<?php echo esc_attr( $css_prefix ); ?>-tags">
						<?php
						foreach ( $asset_info->get_tags() as $asset_tag ) {
							echo '<li class="' . esc_attr( $css_prefix ) . '-tag"><span class="screen-reader-text">' . esc_html__( 'Tag:', 'fair-explorer' ) . ' </span>' . esc_html( $asset_tag ) . '</li>';
						}
						?>
					</ul>
				</div>
			<?php endif; ?>
		</aside>
	</div>
</div>
