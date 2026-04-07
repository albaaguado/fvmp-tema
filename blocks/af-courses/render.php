<?php
/**
 * Block render: doo/af-courses.
 *
 * Supports FC, FAP, and PIM sections via the postType attribute.
 * Colors and taxonomy names are derived automatically from postType.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

// ── Per-section configuration ─────────────────────────────────────────────────
$post_type = $attributes['postType'] ?? 'doo_accion_formativa';

$section_config = array(
	'doo_accion_formativa' => array(
		'area_tax'   => 'doo_area_tematica',
		'accent'     => '#00a9a5',
		'accent_bg'  => 'rgba(0, 169, 165, 0.08)',
	),
	'doo_accion_fap'       => array(
		'area_tax'   => 'doo_fap_area',
		'accent'     => '#f5a623',
		'accent_bg'  => 'rgba(245, 166, 35, 0.08)',
	),
	'doo_accion_pim'       => array(
		'area_tax'   => 'doo_pim_area',
		'accent'     => '#1e3a5f',
		'accent_bg'  => 'rgba(30, 58, 95, 0.08)',
	),
);

$cfg            = $section_config[ $post_type ] ?? $section_config['doo_accion_formativa'];
$area_taxonomy  = $cfg['area_tax'];
$accent_color   = esc_attr( $cfg['accent'] );
$accent_bg      = esc_attr( $cfg['accent_bg'] );
$inline_style   = "--fc-accent:{$accent_color};--fc-accent-bg:{$accent_bg}";

// ── Taxonomy terms ────────────────────────────────────────────────────────────
$areas = get_terms(
	array(
		'taxonomy'   => $area_taxonomy,
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

$modalities = get_terms(
	array(
		'taxonomy'   => 'doo_modalidad',
		'hide_empty' => false,
		'orderby'    => 'name',
		'order'      => 'ASC',
	)
);

// ── Query ─────────────────────────────────────────────────────────────────────
$query = new WP_Query(
	array(
		'post_type'      => $post_type,
		'posts_per_page' => -1,
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
		'post_status'    => 'publish',
	)
);

// ── SVG icons ─────────────────────────────────────────────────────────────────
$clock_svg    = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
$users_svg    = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>';
$monitor_svg  = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>';
$calendar_svg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';
$arrow_svg    = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>';
?>
<section class="doo-af-courses" style="<?php echo esc_attr( $inline_style ); ?>">
	<div class="doo-af-courses__layout">

		<!-- Sidebar -->
		<aside class="doo-af-sidebar" data-fc-sidebar>
			<h2 class="doo-af-sidebar__title">Filtros</h2>

			<?php if ( ! empty( $areas ) && ! is_wp_error( $areas ) ) : ?>
			<div class="doo-af-sidebar__section">
				<p class="doo-af-sidebar__section-title">Área temática</p>
				<?php foreach ( $areas as $area ) : ?>
				<label class="doo-af-checkbox">
					<input type="checkbox" name="doo_area" value="<?php echo esc_attr( $area->slug ); ?>" />
					<span class="doo-af-checkbox__box"></span>
					<span class="doo-af-checkbox__label"><?php echo esc_html( $area->name ); ?></span>
				</label>
				<?php endforeach; ?>
			</div>
			<div class="doo-af-sidebar__divider"></div>
			<?php endif; ?>

			<div class="doo-af-sidebar__section">
				<p class="doo-af-sidebar__section-title">Modalidad</p>
				<?php if ( ! empty( $modalities ) && ! is_wp_error( $modalities ) ) : ?>
					<?php foreach ( $modalities as $mod ) : ?>
					<label class="doo-af-checkbox">
						<input type="checkbox" name="doo_modality" value="<?php echo esc_attr( $mod->slug ); ?>" />
						<span class="doo-af-checkbox__box"></span>
						<span class="doo-af-checkbox__label"><?php echo esc_html( $mod->name ); ?></span>
					</label>
					<?php endforeach; ?>
				<?php endif; ?>
			</div>
		</aside>

		<!-- Main content -->
		<div class="doo-af-main" data-fc-main>
			<div class="doo-af-main__header">
				<div>
					<p class="doo-af-main__title">Acciones Formativas</p>
					<p class="doo-af-main__count" data-fc-count>
						Mostrando <strong><?php echo esc_html( $query->found_posts ); ?></strong> cursos disponibles
					</p>
				</div>
			</div>

			<div class="doo-af-course-list" data-fc-list>
			<?php if ( $query->have_posts() ) : ?>
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					$code      = get_post_meta( get_the_ID(), 'doo_fc_code', true );
					$hours     = get_post_meta( get_the_ID(), 'doo_fc_hours', true );
					$seats     = get_post_meta( get_the_ID(), 'doo_fc_seats', true );
					$raw_dates = get_post_meta( get_the_ID(), 'doo_fc_dates', true );

					$modalidad_terms = get_the_terms( get_the_ID(), 'doo_modalidad' );
					$modality        = ( $modalidad_terms && ! is_wp_error( $modalidad_terms ) ) ? $modalidad_terms[0]->name : '';
					$modality_slug   = ( $modalidad_terms && ! is_wp_error( $modalidad_terms ) ) ? $modalidad_terms[0]->slug : '';

					$linked_page_id = get_post_meta( get_the_ID(), 'doo_linked_page_id', true );
					$link           = $linked_page_id ? get_permalink( $linked_page_id ) : get_permalink( get_the_ID() );

					$area_terms = get_the_terms( get_the_ID(), $area_taxonomy );
					$area_name  = ( $area_terms && ! is_wp_error( $area_terms ) ) ? $area_terms[0]->name : '';
					$area_slug  = ( $area_terms && ! is_wp_error( $area_terms ) ) ? $area_terms[0]->slug : '';

					$dates = $raw_dates ? esc_html( $raw_dates ) : '';
					?>
					<div class="doo-af-card"
						data-area="<?php echo esc_attr( $area_slug ); ?>"
						data-modality="<?php echo esc_attr( $modality_slug ); ?>">
						<div class="doo-af-card__left">
							<div class="doo-af-card__meta">
								<?php if ( $area_name ) : ?>
								<span class="doo-af-card__tag"><?php echo esc_html( $area_name ); ?></span>
								<?php endif; ?>
								<?php if ( $code ) : ?>
								<span class="doo-af-card__code"><?php echo esc_html( $code ); ?></span>
								<?php endif; ?>
							</div>
							<p class="doo-af-card__title"><?php the_title(); ?></p>
							<div class="doo-af-card__attrs">
								<?php if ( $hours ) : ?>
								<div class="doo-af-attr">
									<div class="doo-af-attr__icon"><?php echo $clock_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
									<div class="doo-af-attr__text">
										<span class="doo-af-attr__label">Duración</span>
										<span class="doo-af-attr__value"><?php echo esc_html( $hours ) . ' ' . esc_html__( 'horas', 'dw-tema' ); ?></span>
									</div>
								</div>
								<?php endif; ?>
								<?php if ( $seats ) : ?>
								<div class="doo-af-attr">
									<div class="doo-af-attr__icon"><?php echo $users_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
									<div class="doo-af-attr__text">
										<span class="doo-af-attr__label">Plazas</span>
										<span class="doo-af-attr__value"><?php echo esc_html( $seats ); ?></span>
									</div>
								</div>
								<?php endif; ?>
								<?php if ( $modality ) : ?>
								<div class="doo-af-attr">
									<div class="doo-af-attr__icon"><?php echo $monitor_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
									<div class="doo-af-attr__text">
										<span class="doo-af-attr__label">Modalidad</span>
										<span class="doo-af-attr__value"><?php echo esc_html( $modality ); ?></span>
									</div>
								</div>
								<?php endif; ?>
								<?php if ( $dates ) : ?>
								<div class="doo-af-attr">
									<div class="doo-af-attr__icon"><?php echo $calendar_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
									<div class="doo-af-attr__text">
										<span class="doo-af-attr__label">Fechas</span>
										<span class="doo-af-attr__value"><?php echo $dates; // Already escaped above ?></span>
									</div>
								</div>
								<?php endif; ?>
							</div>
						</div>
						<a class="doo-af-card__btn" href="<?php echo esc_url( $link ); ?>"><span>Ver más información</span><?php echo $arrow_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
					</div>
				<?php endwhile; ?>
				<?php wp_reset_postdata(); ?>
			<?php else : ?>
				<p class="doo-af-course-list__empty">No hay acciones formativas publicadas todavía.</p>
			<?php endif; ?>
			</div>
		</div>

	</div>
</section>
