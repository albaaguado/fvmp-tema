<?php
/**
 * Shortcodes for the Formación Continua page.
 *
 * [doo_fc_hero]    — Hero section with stats and CTA card.
 * [doo_fc_courses] — Sidebar filters + course card list.
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// =========================================================================
// [doo_fc_hero]
// =========================================================================

/**
 * Render the FC hero section.
 *
 * @return string HTML output.
 */
function doo_fc_hero_shortcode() {
	$total = wp_count_posts( 'doo_accion_formativa' );
	$count = isset( $total->publish ) ? (int) $total->publish : 0;

	ob_start();
	?>
	<section class="doo-fc-hero">
		<div class="doo-fc-hero__inner">

			<div class="doo-fc-hero__left">
				<p class="doo-fc-hero__eyebrow">OFERTA FORMATIVA</p>
				<h1 class="doo-fc-hero__title">Formación Continua</h1>
				<p class="doo-fc-hero__desc">La FVMP se reserva el derecho de retirar del Plan FC2026 aquellas acciones formativas que presenten una demanda muy elevada para optimizar el proceso de preinscripción.</p>

				<div class="doo-fc-hero__stats">
					<div class="doo-fc-hero__stat">
						<span class="doo-fc-hero__stat-num"><?php echo esc_html( $count ); ?>+</span>
						<span class="doo-fc-hero__stat-label">acciones formativas</span>
					</div>
					<div class="doo-fc-hero__sep"></div>
					<div class="doo-fc-hero__stat">
						<span class="doo-fc-hero__stat-num">7000+</span>
						<span class="doo-fc-hero__stat-label">plazas disponibles</span>
					</div>
					<div class="doo-fc-hero__sep"></div>
					<div class="doo-fc-hero__stat">
						<span class="doo-fc-hero__stat-num">3</span>
						<span class="doo-fc-hero__stat-label">modalidades</span>
					</div>
				</div>
			</div>

			<div class="doo-fc-hero__card">
				<div class="doo-fc-hero__card-stripe"></div>
				<div class="doo-fc-hero__card-icon">
					<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
				</div>
				<p class="doo-fc-hero__card-title">Preparando la oferta formativa para 2026</p>
				<p class="doo-fc-hero__card-desc">Próximamente estará disponible el catálogo de cursos FC2026. Es necesario iniciar sesión para inscribirse.</p>
				<a class="doo-fc-hero__card-btn" href="/iniciar-sesion/">Iniciar sesión</a>
				<p class="doo-fc-hero__card-register"><a href="/registro/">¿No tienes cuenta? Regístrate →</a></p>
			</div>

		</div>
	</section>
	<?php
	$output = ob_get_clean();
	$output = preg_replace( '/>\s+</', '><', $output );
	return $output;
}
add_shortcode( 'doo_fc_hero', 'doo_fc_hero_shortcode' );

// =========================================================================
// [doo_fc_courses]
// =========================================================================

/**
 * Render the FC courses section (sidebar + course list).
 *
 * @return string HTML output.
 */
function doo_fc_courses_shortcode() {
	$areas = get_terms(
		array(
			'taxonomy'   => 'doo_area_tematica',
			'hide_empty' => false,
			'orderby'    => 'name',
			'order'      => 'ASC',
		)
	);

	$modalities = array(
		'Online',
		'Presencial',
		'Streaming',
		'Semipresencial',
		'Mix Online - Presencial',
		'Mix Online - Streaming',
	);

	$query = new WP_Query(
		array(
			'post_type'      => 'doo_accion_formativa',
			'posts_per_page' => -1,
			'orderby'        => 'menu_order',
			'order'          => 'ASC',
			'post_status'    => 'publish',
		)
	);

	$clock_svg    = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';
	$users_svg    = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>';
	$monitor_svg  = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>';
	$calendar_svg = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>';
	$arrow_svg    = '<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"/></svg>';

	ob_start();
	?>
	<section class="doo-fc-courses">
		<div class="doo-fc-courses__layout">

			<!-- Sidebar -->
			<aside class="doo-fc-sidebar" data-fc-sidebar>
				<h2 class="doo-fc-sidebar__title">Filtros</h2>

				<?php if ( ! empty( $areas ) && ! is_wp_error( $areas ) ) : ?>
				<div class="doo-fc-sidebar__section">
					<p class="doo-fc-sidebar__section-title">Área temática</p>
					<?php foreach ( $areas as $area ) : ?>
					<label class="doo-fc-checkbox">
						<input type="checkbox" name="doo_area" value="<?php echo esc_attr( $area->slug ); ?>" />
						<span class="doo-fc-checkbox__box"></span>
						<span class="doo-fc-checkbox__label"><?php echo esc_html( $area->name ); ?></span>
					</label>
					<?php endforeach; ?>
				</div>
				<div class="doo-fc-sidebar__divider"></div>
				<?php endif; ?>

				<div class="doo-fc-sidebar__section">
					<p class="doo-fc-sidebar__section-title">Modalidad</p>
					<?php foreach ( $modalities as $mod ) : ?>
					<label class="doo-fc-checkbox">
						<input type="checkbox" name="doo_modality" value="<?php echo esc_attr( $mod ); ?>" />
						<span class="doo-fc-checkbox__box"></span>
						<span class="doo-fc-checkbox__label"><?php echo esc_html( $mod ); ?></span>
					</label>
					<?php endforeach; ?>
				</div>
			</aside>

			<!-- Main content -->
			<div class="doo-fc-main" data-fc-main>
				<div class="doo-fc-main__header">
					<div>
						<p class="doo-fc-main__title">Acciones Formativas</p>
						<p class="doo-fc-main__count" data-fc-count>
							Mostrando <strong><?php echo esc_html( $query->found_posts ); ?></strong> cursos disponibles
						</p>
					</div>
				</div>

				<div class="doo-fc-course-list" data-fc-list>
				<?php if ( $query->have_posts() ) : ?>
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						$code     = get_post_meta( get_the_ID(), 'doo_fc_code', true );
						$hours    = get_post_meta( get_the_ID(), 'doo_fc_hours', true );
						$seats    = get_post_meta( get_the_ID(), 'doo_fc_seats', true );
						$modality = get_post_meta( get_the_ID(), 'doo_fc_modality', true );
						$from     = get_post_meta( get_the_ID(), 'doo_fc_date_from', true );
						$to       = get_post_meta( get_the_ID(), 'doo_fc_date_to', true );
						$link     = get_post_meta( get_the_ID(), 'doo_fc_link', true );

						$area_terms = get_the_terms( get_the_ID(), 'doo_area_tematica' );
						$area_name  = ( $area_terms && ! is_wp_error( $area_terms ) ) ? $area_terms[0]->name : '';
						$area_slug  = ( $area_terms && ! is_wp_error( $area_terms ) ) ? $area_terms[0]->slug : '';

					$dates = '';
					$months_es = array(
						1 => 'ene', 2 => 'feb', 3 => 'mar', 4 => 'abr',
						5 => 'may', 6 => 'jun', 7 => 'jul', 8 => 'ago',
						9 => 'sep', 10 => 'oct', 11 => 'nov', 12 => 'dic',
					);

					$date_from_obj = $from ? DateTime::createFromFormat( 'd/m/Y', $from ) : null;
					$date_to_obj   = $to ? DateTime::createFromFormat( 'd/m/Y', $to ) : null;

					if ( $date_from_obj && $date_to_obj ) {
						$d1    = (int) $date_from_obj->format( 'd' );
						$m1    = $months_es[ (int) $date_from_obj->format( 'm' ) ];
						$d2    = str_pad( $date_to_obj->format( 'd' ), 2, '0', STR_PAD_LEFT );
						$m2    = $months_es[ (int) $date_to_obj->format( 'm' ) ];
						$dates = esc_html( "$d1 $m1 - $d2 $m2" );
					} elseif ( $date_from_obj ) {
						$d1    = (int) $date_from_obj->format( 'd' );
						$m1    = $months_es[ (int) $date_from_obj->format( 'm' ) ];
						$dates = esc_html( "$d1 $m1" );
					} elseif ( $from ) {
						$dates = esc_html( $from );
					}
						?>
						<div class="doo-fc-card"
							data-area="<?php echo esc_attr( $area_slug ); ?>"
							data-modality="<?php echo esc_attr( $modality ); ?>">
							<div class="doo-fc-card__left">
								<div class="doo-fc-card__meta">
									<?php if ( $area_name ) : ?>
									<span class="doo-fc-card__tag"><?php echo esc_html( $area_name ); ?></span>
									<?php endif; ?>
									<?php if ( $code ) : ?>
									<span class="doo-fc-card__code"><?php echo esc_html( $code ); ?></span>
									<?php endif; ?>
								</div>
								<p class="doo-fc-card__title"><?php the_title(); ?></p>
								<div class="doo-fc-card__attrs">
									<?php if ( $hours ) : ?>
									<div class="doo-fc-attr">
										<div class="doo-fc-attr__icon"><?php echo $clock_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
										<div class="doo-fc-attr__text">
											<span class="doo-fc-attr__label">Duración</span>
											<span class="doo-fc-attr__value"><?php echo esc_html( $hours ); ?> horas</span>
										</div>
									</div>
									<?php endif; ?>
									<?php if ( $seats ) : ?>
									<div class="doo-fc-attr">
										<div class="doo-fc-attr__icon"><?php echo $users_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
										<div class="doo-fc-attr__text">
											<span class="doo-fc-attr__label">Plazas</span>
											<span class="doo-fc-attr__value"><?php echo esc_html( $seats ); ?> plazas</span>
										</div>
									</div>
									<?php endif; ?>
									<?php if ( $modality ) : ?>
									<div class="doo-fc-attr">
										<div class="doo-fc-attr__icon"><?php echo $monitor_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
										<div class="doo-fc-attr__text">
											<span class="doo-fc-attr__label">Modalidad</span>
											<span class="doo-fc-attr__value"><?php echo esc_html( $modality ); ?></span>
										</div>
									</div>
									<?php endif; ?>
									<?php if ( $dates ) : ?>
									<div class="doo-fc-attr">
										<div class="doo-fc-attr__icon"><?php echo $calendar_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?></div>
										<div class="doo-fc-attr__text">
											<span class="doo-fc-attr__label">Fechas</span>
											<span class="doo-fc-attr__value"><?php echo $dates; // Already escaped above ?></span>
										</div>
									</div>
									<?php endif; ?>
								</div>
							</div>
						<a class="doo-fc-card__btn" href="<?php echo esc_url( $link ? $link : '#' ); ?>"><span>Ver más información</span><?php echo $arrow_svg; // phpcs:ignore WordPress.Security.EscapeOutput ?></a>
						</div>
					<?php endwhile; ?>
					<?php wp_reset_postdata(); ?>
				<?php else : ?>
					<p class="doo-fc-course-list__empty">No hay acciones formativas publicadas todavía.</p>
				<?php endif; ?>
				</div>
			</div>

		</div>
	</section>
	<?php
	$output = ob_get_clean();
	// Strip whitespace between HTML tags so wpautop does not inject <br>.
	$output = preg_replace( '/>\s+</', '><', $output );
	return $output;
}
add_shortcode( 'doo_fc_courses', 'doo_fc_courses_shortcode' );
