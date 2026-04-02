<?php
/**
 * doo/af-curso — Server-side render.
 *
 * Reusable course detail page. Color theming via CSS custom properties
 * (accentColor, accentBg, navyColor, borderColor attributes).
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ── Section colors mapping ─────────────────────────────────────────────────────
$section_colors = [
	'fc' => [
		'label' => 'Formación Continua',
		'slug' => 'formacion-continua',
		'accentColor' => '#009e96',
		'accentBg' => '#e0f5f4',
		'navyColor' => '#1e3a5f',
		'borderColor' => '#0d9488',
		'objectivesBg' => '#F0FBFA',
		'characteristicsBg' => '',
	],
	'fap' => [
		'label' => 'Formación para Equipos de Atención Primaria',
		'slug' => 'fap',
		'accentColor' => '#f5a623',
		'accentBg' => '#ffe8cc',
		'navyColor' => '#1e3a5f',
		'borderColor' => '#f5a623',
		'objectivesBg' => '#FFF4E5',
		'characteristicsBg' => '',
	],
	'pim' => [
		'label' => 'Programa de Innovación Municipal',
		'slug' => 'pim',
		'accentColor' => '#1a2b4a',
		'accentBg' => '#d4dce8',
		'navyColor' => '#1a2b4a',
		'borderColor' => '#1a2b4a',
		'objectivesBg' => '#EEF2F8',
		'characteristicsBg' => '#E2E6EE',
	],
];

// ── Resolve the CPT ID: either we ARE on the CPT, or we're on a linked page ───
$current_id   = get_the_ID();
$is_cpt       = ( in_array( get_post_type( $current_id ), [ 'doo_accion_formativa', 'doo_accion_fap', 'doo_accion_pim' ], true ) );
$linked_cpt_id = $is_cpt ? $current_id : (int) get_post_meta( $current_id, 'doo_linked_cpt_id', true );

// Auto-detect section based on CPT type if not explicitly set
if ( ! isset( $attributes['section'] ) || empty( $attributes['section'] ) ) {
	$cpt_type = get_post_type( $linked_cpt_id );
	if ( 'doo_accion_fap' === $cpt_type ) {
		$attributes['section'] = 'fap';
	} elseif ( 'doo_accion_pim' === $cpt_type ) {
		$attributes['section'] = 'pim';
	} else {
		$attributes['section'] = 'fc';
	}
}

$section_value = $attributes['section'] ?? 'fc';

// Apply section colors if not explicitly overridden in attributes
$colors = $section_colors[ $section_value ] ?? $section_colors['fc'];
$accent_color       = esc_attr( ! empty( $attributes['accentColor'] )       ? $attributes['accentColor']       : $colors['accentColor'] );
$accent_bg          = esc_attr( ! empty( $attributes['accentBg'] )          ? $attributes['accentBg']          : $colors['accentBg'] );
$navy_color         = esc_attr( ! empty( $attributes['navyColor'] )         ? $attributes['navyColor']         : $colors['navyColor'] );
$border_color       = esc_attr( ! empty( $attributes['borderColor'] )       ? $attributes['borderColor']       : $colors['borderColor'] );
$objectives_bg      = esc_attr( ! empty( $attributes['objectivesBg'] )      ? $attributes['objectivesBg']      : $colors['objectivesBg'] );
$characteristics_bg = esc_attr( ! empty( $attributes['characteristicsBg'] ) ? $attributes['characteristicsBg'] : $colors['characteristicsBg'] );
$section_label      = ! empty( $attributes['sectionLabel'] ) ? $attributes['sectionLabel'] : $colors['label'];
$section_slug       = ! empty( $attributes['sectionSlug'] )  ? $attributes['sectionSlug']  : $colors['slug'];

if ( $linked_cpt_id ) {
	$course_title    = get_the_title( $linked_cpt_id );
	$course_code     = get_post_meta( $linked_cpt_id, 'doo_fc_code', true );
	$duration        = get_post_meta( $linked_cpt_id, 'doo_fc_hours', true );
	$capacity        = get_post_meta( $linked_cpt_id, 'doo_fc_seats', true );
	$fecha           = get_post_meta( $linked_cpt_id, 'doo_fc_dates', true );
	$modalidad_terms = get_the_terms( $linked_cpt_id, 'doo_modalidad' );
	$modality        = ( $modalidad_terms && ! is_wp_error( $modalidad_terms ) ) ? $modalidad_terms[0]->name : '';
	$location        = 'España';
} else {
	$course_title = $attributes['courseTitle'] ?? '';
	$course_code  = $attributes['courseCode']  ?? '';
	$modality     = $attributes['modality']    ?? '';
	$location     = $attributes['location']    ?? '';
	$duration     = $attributes['duration']    ?? '';
	$capacity     = $attributes['capacity']    ?? '';
	$fecha        = $attributes['fecha']       ?? '';
}

// Content data always from attributes (user-editable in InspectorControls)
$objectives_intro = $attributes['objectivesIntro'] ?? '';
$objectives       = $attributes['objectives']      ?? array();
$topics           = $attributes['topics']          ?? array();
$destinatarios    = $attributes['destinatarios']   ?? '';
$horario          = $attributes['horario']         ?? '';
$inscribirse_url  = $attributes['inscribirseUrl']  ?? '#';
$listado_url      = $attributes['listadoUrl']      ?? "/{$section_slug}/";

// ── Inline SVG helper (closure avoids redeclaration on multiple renders) ──────
$icon = static function ( string $name, int $size = 16 ) : string {
	$s = "width=\"{$size}\" height=\"{$size}\" viewBox=\"0 0 24 24\" fill=\"none\" aria-hidden=\"true\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\"";
	$p = array(
		'graduation-cap' => '<path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>',
		'laptop-minimal' => '<rect x="2" y="3" width="20" height="14" rx="2"/><line x1="2" y1="21" x2="22" y2="21"/>',
		'map-pin'        => '<path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>',
		'timer'          => '<line x1="10" x2="14" y1="2" y2="2"/><line x1="12" x2="15" y1="14" y2="11"/><circle cx="12" cy="14" r="8"/>',
		'users'          => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
		'calendar'       => '<rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" x2="16" y1="2" y2="6"/><line x1="8" x2="8" y1="2" y2="6"/><line x1="3" x2="21" y1="10" y2="10"/>',
		'clock'          => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
		'arrow-up-right' => '<path d="M7 7h10v10"/><path d="M7 17 17 7"/>',
	);
	$d = $p[ $name ] ?? '';
	return "<svg xmlns=\"http://www.w3.org/2000/svg\" {$s}>{$d}</svg>";
};

// ── Build meta pills ───────────────────────────────────────────────────────────
$meta_pills = array();
if ( $course_code ) $meta_pills[] = array( 'icon' => 'graduation-cap', 'label' => $course_code );
if ( $modality    ) $meta_pills[] = array( 'icon' => 'laptop-minimal',  'label' => $modality );
if ( $location    ) $meta_pills[] = array( 'icon' => 'map-pin',         'label' => $location );
if ( $duration    ) $meta_pills[] = array( 'icon' => 'timer',           'label' => $duration . ' h' );
if ( $capacity    ) $meta_pills[] = array( 'icon' => 'users',           'label' => $capacity . ' plazas' );

// ── CSS custom properties for color theming ───────────────────────────────────
$style_parts = array(
	"--fc-accent:{$accent_color}",
	"--fc-accent-bg:{$accent_bg}",
	"--fc-navy:{$navy_color}",
	"--fc-border:{$border_color}",
	"--fc-obj-bg:{$objectives_bg}",
	"--fc-pill-bg:{$accent_bg}",
	"--fc-pill-color:{$accent_color}",
);
if ( $characteristics_bg ) {
	$style_parts[] = "--fc-char-bg:{$characteristics_bg}";
}
$inline_style = implode( ';', $style_parts );
?>
<div class="doo-af-curso" style="<?php echo esc_attr( $inline_style ); ?>">

	<!-- ── Hero ──────────────────────────────────────────────────────────── -->
	<div class="doo-af-curso__hero">
		<div class="doo-af-curso__hero-inner">

			<nav class="doo-af-curso__breadcrumb" aria-label="<?php esc_attr_e( 'Migas de pan', 'dw-tema' ); ?>">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="doo-af-curso__breadcrumb-link"><?php esc_html_e( 'Inicio', 'dw-tema' ); ?></a>
				<span class="doo-af-curso__breadcrumb-sep" aria-hidden="true">/</span>
				<a href="<?php echo esc_url( home_url( "/{$section_slug}/" ) ); ?>" class="doo-af-curso__breadcrumb-link"><?php echo esc_html( $section_label ); ?></a>
				<span class="doo-af-curso__breadcrumb-sep" aria-hidden="true">/</span>
				<span class="doo-af-curso__breadcrumb-current"><?php echo esc_html( $course_title ); ?></span>
			</nav>

			<h1 class="doo-af-curso__title"><?php echo esc_html( $course_title ); ?></h1>

			<?php if ( $meta_pills ) : ?>
			<div class="doo-af-curso__meta" role="list">
				<?php foreach ( $meta_pills as $pill ) : ?>
				<div class="doo-af-curso__pill" role="listitem">
					<?php echo $icon( $pill['icon'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted SVG helper ?>
					<span><?php echo esc_html( $pill['label'] ); ?></span>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endif; ?>

		</div>
	</div>

	<!-- ── Body ──────────────────────────────────────────────────────────── -->
	<div class="doo-af-curso__body">
		<div class="doo-af-curso__body-inner">

			<!-- Left column -->
			<div class="doo-af-curso__main">

				<!-- Objetivos -->
				<?php if ( $objectives ) : ?>
				<section class="doo-af-curso__section doo-af-curso__section--objetivos">
					<h2 class="doo-af-curso__section-title"><?php esc_html_e( 'OBJETIVOS', 'dw-tema' ); ?></h2>
					<div class="doo-af-curso__section-bar" aria-hidden="true"></div>

					<?php if ( $objectives_intro ) : ?>
					<p class="doo-af-curso__section-intro"><?php echo esc_html( $objectives_intro ); ?></p>
					<?php endif; ?>

					<div class="doo-af-curso__objectives-grid">
						<?php foreach ( $objectives as $index => $obj ) : ?>
						<div class="doo-af-curso__obj-card">
							<span class="doo-af-curso__obj-badge" aria-hidden="true"><?php echo esc_html( $index + 1 ); ?></span>
							<p><?php echo esc_html( $obj['text'] ?? '' ); ?></p>
						</div>
						<?php endforeach; ?>
					</div>
				</section>
				<?php endif; ?>

				<!-- Contenido -->
				<?php if ( $topics ) : ?>
				<section class="doo-af-curso__section doo-af-curso__section--contenido">
					<h2 class="doo-af-curso__section-title"><?php esc_html_e( 'CONTENIDO', 'dw-tema' ); ?></h2>
					<div class="doo-af-curso__section-bar" aria-hidden="true"></div>

					<div class="doo-af-curso__topics">
						<?php foreach ( $topics as $t_idx => $topic ) : ?>
						<div class="doo-af-curso__topic">
							<div class="doo-af-curso__topic-header">
								<span class="doo-af-curso__topic-badge" aria-hidden="true"><?php echo esc_html( $t_idx + 1 ); ?></span>
								<h3 class="doo-af-curso__topic-title"><?php echo esc_html( $topic['title'] ?? '' ); ?></h3>
							</div>

							<?php if ( ! empty( $topic['items'] ) ) : ?>
							<div class="doo-af-curso__topic-content">
								<?php foreach ( $topic['items'] as $item ) :
									$item = trim( (string) $item );
									if ( '' === $item ) continue;
									if ( 0 === strpos( $item, '## ' ) ) : ?>
									<p class="doo-af-curso__topic-subheading"><?php echo esc_html( substr( $item, 3 ) ); ?></p>
								<?php else : ?>
									<p><?php echo esc_html( $item ); ?></p>
								<?php endif;
								endforeach; ?>
							</div>
							<?php endif; ?>
						</div>
						<?php endforeach; ?>
					</div>
				</section>
				<?php endif; ?>

			</div>

			<!-- Right sidebar -->
			<aside class="doo-af-curso__sidebar">

				<div class="doo-af-curso__sidebar-card">

					<?php if ( $destinatarios ) : ?>
					<div class="doo-af-curso__dest">
						<div class="doo-af-curso__dest-header">
							<strong><?php esc_html_e( 'Destinatarios', 'dw-tema' ); ?></strong>
						</div>
						<div class="doo-af-curso__dest-line"></div>
						<div class="doo-af-curso__dest-body">
							<p><?php echo esc_html( $destinatarios ); ?></p>
						</div>
					</div>
					<?php endif; ?>

					<?php if ( $horario ) : ?>
					<div class="doo-af-curso__info-card" style="margin-top:16px">
						<div class="doo-af-curso__info-card-header">
							<span class="doo-af-curso__info-card-icon"><?php echo $icon( 'timer', 18 ); // phpcs:ignore ?></span>
							<p class="doo-af-curso__info-card-label"><?php esc_html_e( 'Horario', 'dw-tema' ); ?></p>
						</div>
						<p class="doo-af-curso__info-card-value"><?php echo esc_html( $horario ); ?></p>
					</div>
					<?php endif; ?>

					<?php if ( $fecha ) : ?>
					<div class="doo-af-curso__info-card">
						<div class="doo-af-curso__info-card-header">
							<span class="doo-af-curso__info-card-icon"><?php echo $icon( 'calendar', 18 ); // phpcs:ignore ?></span>
							<p class="doo-af-curso__info-card-label"><?php esc_html_e( 'Fecha', 'dw-tema' ); ?></p>
						</div>
						<p class="doo-af-curso__info-card-value"><?php echo esc_html( $fecha ); ?></p>
					</div>
					<?php endif; ?>

					<a href="<?php echo esc_url( $inscribirse_url ); ?>" class="doo-af-curso__inscribirse-btn">
						<?php esc_html_e( 'INSCRÍBETE', 'dw-tema' ); ?>
					</a>

				</div>

				<div class="doo-af-curso__listado-card">
					<p><?php esc_html_e( 'Accede al listado completo de cursos', 'dw-tema' ); ?></p>
					<a href="<?php echo esc_url( $listado_url ); ?>" class="doo-af-curso__listado-btn">
						<?php esc_html_e( 'Listado de cursos', 'dw-tema' ); ?>
						<?php echo $icon( 'arrow-up-right', 14 ); // phpcs:ignore ?>
					</a>
				</div>

			</aside>

		</div>
	</div>

</div>
