<?php
/**
 * FairExplorer Extension Card (Single View)
 *
 * Displays a single TYPO3 extension's details, sections, meta, and ratings.
 *
 * @package FairExplorer
 */

/**
 * @var FairExplorer\Model\ExtensionInfo $extension_info.
 */
$extension_info = $args['extension_info'] ?? [];

$banner_url = $extension_info->get_banners( 'high' );
if ( empty( $banner_url ) ) {
	$banner_url = AE_DIR_URL . 'assets/images/default-banner.svg';
}

$sections    = $extension_info->get_sections();
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
	$description = $extension_info->get_description();
}
?>
<div class="single-typo3-extension-card">
	<banner class="entry-banner">
		<img class="typo3-extension-banner" src="<?php echo esc_url( $banner_url ); ?>" alt="Extension Banner" fetchpriority="high">
	</banner>
	<header class="entry-header">
		<div class="entry-title">
			<h2 class="typo3-extension-title"><?php echo esc_html( $extension_info->get_name() ); ?></h2>
			<p class="typo3-extension-author">by <?php echo esc_html( $extension_info->get_author( 'display_name' ) ); ?></p>
		</div>
		<div class="entry-download">
			<a href="<?php echo esc_url( $extension_info->get_download_link() ); ?>" class="button button-primary" download rel="noopener noreferrer"><span class="dashicons dashicons-download"></span> <?php esc_html_e( 'Download', 'fair-explorer' ); ?></a>
		</div>
	</header>
	<div class="entry-main">
		<article>
			<?php
			if ( is_array( $sections ) && count( $sections ) > 0 ) {
				$priority_order = [
					'description',
					'installation',
					'screenshots',
					'faq',
					'support',
					'reviews',
					'changelog',
					'other_notes',
				];
				uksort(
					$sections,
					function ( $a, $b ) use ( $priority_order ) {
						$pos_a = array_search( $a, $priority_order, true );
						$pos_b = array_search( $b, $priority_order, true );
						$pos_a = ( false === $pos_a ) ? PHP_INT_MAX : $pos_a;
						$pos_b = ( false === $pos_b ) ? PHP_INT_MAX : $pos_b;
						return $pos_a - $pos_b;
					}
				);

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
			}
			?>
		</article>
		<aside aria-label="<?php esc_attr_e( 'Extension Metadata', 'fair-explorer' ); ?>">
			<ul>
				<?php
				$extension_key = $extension_info->get_extension_key();
				if ( $extension_key ) {
					echo '<li class="typo3-extension-meta-item"><strong>' . esc_html__( 'Extension Key', 'fair-explorer' ) . ':</strong> <code>' . esc_html( $extension_key ) . '</code></li>';
				}

				$meta_data = [
					'Version'         => $extension_info->get_version(),
					'Active Installs' => $extension_info->get_active_installs(),
					'Last Updated'    => $extension_info->get_last_updated(),
				];

				$compatibility = $extension_info->get_compatibility();
				if ( ! empty( $compatibility ) ) {
					$meta_data['TYPO3 Compatibility'] = implode( ', ', $compatibility );
				}

				foreach ( $meta_data as $key => $value ) {
					if ( empty( $value ) ) {
						continue;
					}
					if ( is_array( $value ) ) {
						$value = implode( ', ', $value );
					}
					echo '<li class="typo3-extension-meta-item"><strong>' . esc_html( $key ) . ':</strong> ' . esc_html( $value ) . '</li>';
				}
				?>
			</ul>
			<div class="ratings">
				<?php
				$total   = 0;
				$sum     = 0;
				$ratings = $extension_info->get_ratings();
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
		</aside>
	</div>
</div>
