<?php
/**
 * Block render: doo/mis-cursos.
 *
 * Displays the requested courses for the logged-in user.
 * Course data is hardcoded as placeholder — will be replaced by backend integration.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block content.
 * @var WP_Block $block      Block instance.
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section_title  = ! empty( $attributes['sectionTitle'] )  ? $attributes['sectionTitle']  : __( 'Cursos solicitados', 'dw-tema' );
$info_text      = ! empty( $attributes['infoText'] )      ? $attributes['infoText']      : __( 'Puedes solicitar hasta un máximo de 4 cursos por plan y ordenarlos por orden de preferencia.', 'dw-tema' );
$warning_text   = ! empty( $attributes['warningText'] )   ? $attributes['warningText']   : __( 'No olvides Enviar la Solicitud una vez hayas seleccionado y ordenado los cursos según tus preferencias.', 'dw-tema' );
$program1_title = ! empty( $attributes['program1Title'] ) ? $attributes['program1Title'] : __( 'Programa Innovación Municipal', 'dw-tema' );
$program2_title = ! empty( $attributes['program2Title'] ) ? $attributes['program2Title'] : __( 'Formación Equipos Atención Primaria', 'dw-tema' );
$btn_ver_mas    = ! empty( $attributes['btnVerMasLabel'] ) ? $attributes['btnVerMasLabel'] : __( 'Ver más cursos', 'dw-tema' );
$btn_enviar     = ! empty( $attributes['btnEnviarLabel'] ) ? $attributes['btnEnviarLabel'] : __( 'Enviar solicitud', 'dw-tema' );

/**
 * Placeholder course programs.
 * TODO: Replace with real backend data when API is available.
 */
$programs = array(
	array(
		'title'   => $program1_title,
		'courses' => array(
			array(
				'order'  => 1,
				'code'   => 'IM01.1',
				'name'   => 'Microcredencial Universitària en Innovació Local',
				'start'  => '11/04/2025',
				'end'    => '27/06/2025',
				'status' => __( 'Pendiente', 'dw-tema' ),
			),
		),
	),
	array(
		'title'   => $program2_title,
		'courses' => array(
			array(
				'order'  => 1,
				'code'   => 'AP01.1',
				'name'   => 'Entrenament en benestar social per als professionals de serveis socials municipals',
				'start'  => '31/03/2025',
				'end'    => '08/04/2025',
				'status' => __( 'Pendiente', 'dw-tema' ),
			),
		),
	),
);

/**
 * Helper: render one table row.
 *
 * @param array $course Course data array.
 */
if ( ! function_exists( 'doo_mis_cursos_render_row' ) ) :
function doo_mis_cursos_render_row( array $course ) {
	?>
	<div class="doo-mis-cursos__row doo-mis-cursos__row--data" role="row">

		<div class="doo-mis-cursos__cell doo-mis-cursos__cell--order" role="cell">
			<?php echo esc_html( $course['order'] ); ?>
		</div>

		<div class="doo-mis-cursos__cell doo-mis-cursos__cell--code" role="cell">
			<?php echo esc_html( $course['code'] ); ?>
		</div>

		<div class="doo-mis-cursos__cell doo-mis-cursos__cell--name" role="cell">
			<?php echo esc_html( $course['name'] ); ?>
		</div>

		<div class="doo-mis-cursos__cell doo-mis-cursos__cell--start" role="cell">
			<?php echo esc_html( $course['start'] ); ?>
		</div>

		<div class="doo-mis-cursos__cell doo-mis-cursos__cell--end" role="cell">
			<?php echo esc_html( $course['end'] ); ?>
		</div>

		<div class="doo-mis-cursos__cell doo-mis-cursos__cell--status" role="cell">
			<?php echo esc_html( $course['status'] ); ?>
		</div>

		<div class="doo-mis-cursos__cell doo-mis-cursos__cell--cert" role="cell"></div>

		<div class="doo-mis-cursos__cell doo-mis-cursos__cell--sort" role="cell">
			<button class="doo-mis-cursos__sort-btn" type="button" aria-label="<?php esc_attr_e( 'Subir posición', 'dw-tema' ); ?>">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<polyline points="18 15 12 9 6 15"/>
				</svg>
			</button>
			<button class="doo-mis-cursos__sort-btn" type="button" aria-label="<?php esc_attr_e( 'Bajar posición', 'dw-tema' ); ?>">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<polyline points="6 9 12 15 18 9"/>
				</svg>
			</button>
		</div>

		<div class="doo-mis-cursos__cell doo-mis-cursos__cell--delete" role="cell">
			<button class="doo-mis-cursos__delete-btn" type="button" aria-label="<?php esc_attr_e( 'Eliminar curso', 'dw-tema' ); ?>">
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
					<line x1="18" y1="6" x2="6" y2="18"/>
					<line x1="6" y1="6" x2="18" y2="18"/>
				</svg>
			</button>
		</div>

	</div>
	<?php
}
endif;
?>

<div class="doo-mis-cursos">
	<div class="doo-mis-cursos__content">

		<h1 class="doo-mis-cursos__title"><?php echo esc_html( $section_title ); ?></h1>

		<p class="doo-mis-cursos__info"><?php echo esc_html( $info_text ); ?></p>

		<p class="doo-mis-cursos__warning"><?php echo esc_html( $warning_text ); ?></p>

		<?php foreach ( $programs as $program ) : ?>

			<h2 class="doo-mis-cursos__program-title"><?php echo esc_html( $program['title'] ); ?></h2>

			<div class="doo-mis-cursos__table-wrap" role="table" aria-label="<?php echo esc_attr( $program['title'] ); ?>">

				<div class="doo-mis-cursos__row doo-mis-cursos__row--header" role="row">
					<div class="doo-mis-cursos__cell doo-mis-cursos__cell--order"  role="columnheader"><?php esc_html_e( 'Orden',       'dw-tema' ); ?></div>
					<div class="doo-mis-cursos__cell doo-mis-cursos__cell--code"   role="columnheader"><?php esc_html_e( 'Código',      'dw-tema' ); ?></div>
					<div class="doo-mis-cursos__cell doo-mis-cursos__cell--name"   role="columnheader"><?php esc_html_e( 'Curso',       'dw-tema' ); ?></div>
					<div class="doo-mis-cursos__cell doo-mis-cursos__cell--start"  role="columnheader"><?php esc_html_e( 'Inicio',      'dw-tema' ); ?></div>
					<div class="doo-mis-cursos__cell doo-mis-cursos__cell--end"    role="columnheader"><?php esc_html_e( 'Fin',         'dw-tema' ); ?></div>
					<div class="doo-mis-cursos__cell doo-mis-cursos__cell--status" role="columnheader"><?php esc_html_e( 'Estado',      'dw-tema' ); ?></div>
					<div class="doo-mis-cursos__cell doo-mis-cursos__cell--cert"   role="columnheader"><?php esc_html_e( 'Certificado', 'dw-tema' ); ?></div>
					<div class="doo-mis-cursos__cell doo-mis-cursos__cell--sort"   role="columnheader"><?php esc_html_e( 'Ordenar',     'dw-tema' ); ?></div>
					<div class="doo-mis-cursos__cell doo-mis-cursos__cell--delete" role="columnheader"><?php esc_html_e( 'Borrar',      'dw-tema' ); ?></div>
				</div>

				<?php foreach ( $program['courses'] as $course ) : ?>
					<?php doo_mis_cursos_render_row( $course ); ?>
				<?php endforeach; ?>

			</div>

		<?php endforeach; ?>

		<div class="doo-mis-cursos__actions">
			<button class="doo-mis-cursos__btn doo-mis-cursos__btn--outline" type="button">
				<?php echo esc_html( $btn_ver_mas ); ?>
			</button>
			<button class="doo-mis-cursos__btn doo-mis-cursos__btn--primary" type="button">
				<?php echo esc_html( $btn_enviar ); ?>
			</button>
		</div>

	</div>
</div>
