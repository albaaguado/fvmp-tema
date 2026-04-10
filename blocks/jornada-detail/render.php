<?php
/**
 * Jornada Detail Block Render Template.
 *
 * Displays the detail page for a single Jornada:
 * header area (breadcrumb + title + meta), presentations grid, videos grid.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content (unused).
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Extract YouTube video ID from a URL.
 *
 * Supports youtube.com/watch?v=, youtu.be/, and embed/ formats.
 *
 * @param string $url YouTube URL.
 * @return string Video ID, or empty string if not found.
 */
if ( ! function_exists( 'doo_jornada_detail_get_yt_id' ) ) {
	function doo_jornada_detail_get_yt_id( $url ) {
		if ( empty( $url ) ) {
			return '';
		}
		preg_match(
			'/(?:youtube\.com\/(?:watch\?v=|embed\/|v\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
			$url,
			$matches
		);
		return ! empty( $matches[1] ) ? $matches[1] : '';
	}
}

// Resolve linked CPT from the current page.
$page_id       = get_the_ID();
$linked_cpt_id = get_post_meta( $page_id, 'doo_linked_cpt_id', true );

if ( ! $linked_cpt_id ) {
	echo '<p>' . esc_html__( 'No se encontró la jornada vinculada.', 'dw-tema' ) . '</p>';
	return;
}

$jornada = get_post( $linked_cpt_id );

if ( ! $jornada || 'doo_jornada' !== $jornada->post_type ) {
	echo '<p>' . esc_html__( 'La jornada no existe o ha sido eliminada.', 'dw-tema' ) . '</p>';
	return;
}

$title = $jornada->post_title;
$date  = get_post_meta( $linked_cpt_id, 'doo_jornada_date', true );

// Block attributes with safe defaults.
$location      = ! empty( $attributes['location'] ) ? $attributes['location'] : 'Valencia';
$presentations = ! empty( $attributes['presentations'] ) && is_array( $attributes['presentations'] )
	? $attributes['presentations']
	: array();
$videos        = ! empty( $attributes['videos'] ) && is_array( $attributes['videos'] )
	? $attributes['videos']
	: array();

// SVG icon paths (inline, no external dependencies).
$icon_chevron  = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"/></svg>';
$icon_calendar = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';
$icon_mappin   = '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg>';
$icon_filetext = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>';
$icon_doc_lg   = '<svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><polyline points="10 9 9 9 8 9"/></svg>';
$icon_download = '<svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>';
$icon_youtube  = '<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.95-1.96C18.88 4 12 4 12 4s-6.88 0-8.59.46a2.78 2.78 0 0 0-1.95 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58a2.78 2.78 0 0 0 1.95 1.96C5.12 20 12 20 12 20s6.88 0 8.59-.46a2.78 2.78 0 0 0 1.95-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>';
$icon_play     = '<svg xmlns="http://www.w3.org/2000/svg" width="26" height="26" viewBox="0 0 24 24" fill="white" stroke="none" aria-hidden="true"><polygon points="5 3 19 12 5 21 5 3"/></svg>';
?>

<article class="doo-jornada-detail">

	<!-- ============================================================
	     Header Area: white background, breadcrumb + title + meta
	     ============================================================ -->
	<div class="doo-jornada-detail__header-area">
		<div class="doo-jornada-detail__header-inner">

			<nav class="doo-jornada-detail__breadcrumb" aria-label="<?php esc_attr_e( 'Migas de pan', 'dw-tema' ); ?>">
				<a href="<?php echo esc_url( home_url( '/jornadas/' ) ); ?>" class="doo-jornada-detail__breadcrumb-link">
					<?php esc_html_e( 'Jornadas', 'dw-tema' ); ?>
				</a>
				<?php echo $icon_chevron; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span class="doo-jornada-detail__breadcrumb-current"><?php echo esc_html( $title ); ?></span>
			</nav>

			<h1 class="doo-jornada-detail__title"><?php echo esc_html( $title ); ?></h1>

			<div class="doo-jornada-detail__meta-row">
				<?php echo $icon_calendar; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
				<span><?php echo esc_html( $date ); ?></span>
				<?php if ( $location ) : ?>
					<span class="doo-jornada-detail__meta-dot" aria-hidden="true"></span>
					<?php echo $icon_mappin; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<span><?php echo esc_html( $location ); ?></span>
				<?php endif; ?>
			</div>

		</div>
	</div>

	<!-- ============================================================
	     Content Area: light gray background
	     ============================================================ -->
	<div class="doo-jornada-detail__content-area">
		<div class="doo-jornada-detail__content-inner">

			<?php if ( ! empty( $presentations ) ) : ?>
			<!-- Presentations Section -->
			<section class="doo-jornada-detail__docs-section">
				<div class="doo-jornada-detail__section-header">
					<?php echo $icon_filetext; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<h2><?php esc_html_e( 'Presentaciones', 'dw-tema' ); ?></h2>
				</div>

				<div class="doo-jornada-detail__docs-grid">
					<?php foreach ( $presentations as $pres ) :
						$pres_title     = ! empty( $pres['title'] ) ? $pres['title'] : '';
						$pres_file_type = ! empty( $pres['fileType'] ) ? $pres['fileType'] : 'PDF';
						$pres_url       = ! empty( $pres['url'] ) ? $pres['url'] : '#';
					?>
					<div class="doo-jornada-detail__doc-card">
						<div class="doo-jornada-detail__doc-icon-row">
							<?php echo $icon_doc_lg; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<span class="doo-jornada-detail__doc-badge"><?php echo esc_html( $pres_file_type ); ?></span>
						</div>
						<p class="doo-jornada-detail__doc-title"><?php echo esc_html( $pres_title ); ?></p>
						<a href="<?php echo esc_url( $pres_url ); ?>" class="doo-jornada-detail__doc-download" download>
							<?php echo $icon_download; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
							<?php esc_html_e( 'Descargar', 'dw-tema' ); ?>
						</a>
					</div>
					<?php endforeach; ?>
				</div>
			</section>
			<?php endif; ?>

			<?php if ( ! empty( $videos ) ) : ?>
			<!-- Videos Section -->
			<section class="doo-jornada-detail__videos-section">
				<div class="doo-jornada-detail__section-header">
					<?php echo $icon_youtube; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
					<h2><?php esc_html_e( 'Vídeos', 'dw-tema' ); ?></h2>
				</div>

				<div class="doo-jornada-detail__videos-grid">
					<?php foreach ( $videos as $video ) :
						$vid_title = ! empty( $video['title'] ) ? $video['title'] : '';
						$vid_url   = ! empty( $video['url'] ) ? $video['url'] : '#';
						$yt_id     = doo_jornada_detail_get_yt_id( $vid_url );
						$thumb_url = $yt_id
							? 'https://img.youtube.com/vi/' . rawurlencode( $yt_id ) . '/hqdefault.jpg'
							: '';
					?>
					<a
						href="<?php echo esc_url( $vid_url ); ?>"
						class="doo-jornada-detail__video-card"
						target="_blank"
						rel="noopener noreferrer"
						aria-label="<?php echo esc_attr( $vid_title ); ?>"
					>
						<?php if ( $thumb_url ) : ?>
							<img
								class="doo-jornada-detail__video-thumb"
								src="<?php echo esc_url( $thumb_url ); ?>"
								alt="<?php echo esc_attr( $vid_title ); ?>"
								loading="lazy"
							/>
						<?php else : ?>
							<div class="doo-jornada-detail__video-thumb-placeholder" aria-hidden="true"></div>
						<?php endif; ?>

						<div class="doo-jornada-detail__video-title-bar">
							<span><?php echo esc_html( $vid_title ); ?></span>
						</div>

						<div class="doo-jornada-detail__video-play" aria-hidden="true">
							<?php echo $icon_play; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</div>
					</a>
					<?php endforeach; ?>
				</div>
			</section>
			<?php endif; ?>

		</div>
	</div>

</article>
