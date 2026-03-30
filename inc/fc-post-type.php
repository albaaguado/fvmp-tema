<?php
/**
 * Custom Post Type: Acción Formativa (doo_accion_formativa).
 *
 * Registers the CPT, custom taxonomy, and meta fields
 * used by the Formación Continua page.
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Acción Formativa CPT.
 *
 * @return void
 */
function doo_register_accion_formativa_cpt() {
	register_post_type(
		'doo_accion_formativa',
		array(
			'labels'       => array(
				'name'               => __( 'Acciones Formativas', 'dw-tema' ),
				'singular_name'      => __( 'Acción Formativa', 'dw-tema' ),
				'add_new_item'       => __( 'Añadir acción formativa', 'dw-tema' ),
				'edit_item'          => __( 'Editar acción formativa', 'dw-tema' ),
				'all_items'          => __( 'Todas las acciones', 'dw-tema' ),
				'search_items'       => __( 'Buscar acciones', 'dw-tema' ),
				'not_found'          => __( 'No se encontraron acciones', 'dw-tema' ),
				'not_found_in_trash' => __( 'No hay acciones en la papelera', 'dw-tema' ),
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'menu_icon'     => 'dashicons-welcome-learn-more',
			'menu_position' => 21,
			'supports'      => array( 'title', 'page-attributes' ),
			'has_archive'   => false,
		)
	);
}
add_action( 'init', 'doo_register_accion_formativa_cpt' );

/**
 * Register the Área Temática taxonomy for Acción Formativa.
 *
 * @return void
 */
function doo_register_area_tematica_taxonomy() {
	register_taxonomy(
		'doo_area_tematica',
		'doo_accion_formativa',
		array(
			'labels'       => array(
				'name'          => __( 'Áreas Temáticas', 'dw-tema' ),
				'singular_name' => __( 'Área Temática', 'dw-tema' ),
				'add_new_item'  => __( 'Añadir área temática', 'dw-tema' ),
				'search_items'  => __( 'Buscar áreas', 'dw-tema' ),
				'all_items'     => __( 'Todas las áreas', 'dw-tema' ),
			),
			'hierarchical'  => true,
			'show_ui'       => true,
			'show_in_rest'  => true,
			'rewrite'       => false,
		)
	);
}
add_action( 'init', 'doo_register_area_tematica_taxonomy' );

/**
 * Register the meta box for Acción Formativa fields.
 *
 * @return void
 */
function doo_accion_formativa_add_meta_box() {
	add_meta_box(
		'doo_accion_formativa_fields',
		__( 'Datos de la acción formativa', 'dw-tema' ),
		'doo_accion_formativa_meta_box_html',
		'doo_accion_formativa',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'doo_accion_formativa_add_meta_box' );

/**
 * Render the Acción Formativa meta box fields.
 *
 * @param WP_Post $post Current post object.
 * @return void
 */
function doo_accion_formativa_meta_box_html( $post ) {
	wp_nonce_field( 'doo_accion_formativa_save', 'doo_accion_formativa_nonce' );

	$fields = array(
		'doo_fc_code'      => 'Código (ej. AE01.1)',
		'doo_fc_hours'     => 'Duración (horas)',
		'doo_fc_seats'     => 'Plazas',
		'doo_fc_modality'  => 'Modalidad',
		'doo_fc_date_from' => 'Fecha inicio (dd/mm/aaaa)',
		'doo_fc_date_to'   => 'Fecha fin (dd/mm/aaaa)',
		'doo_fc_link'      => 'URL de detalle',
	);

	echo '<table class="form-table"><tbody>';
	foreach ( $fields as $key => $label ) {
		$value = get_post_meta( $post->ID, $key, true );
		echo '<tr>';
		echo '<th><label for="' . esc_attr( $key ) . '">' . esc_html( $label ) . '</label></th>';
		echo '<td><input type="text" id="' . esc_attr( $key ) . '" name="' . esc_attr( $key ) . '" value="' . esc_attr( $value ) . '" class="regular-text" /></td>';
		echo '</tr>';
	}
	echo '</tbody></table>';
}

/**
 * Save Acción Formativa meta fields on post save.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function doo_accion_formativa_save_meta( $post_id ) {
	if ( ! isset( $_POST['doo_accion_formativa_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['doo_accion_formativa_nonce'] ) ), 'doo_accion_formativa_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array(
		'doo_fc_code',
		'doo_fc_hours',
		'doo_fc_seats',
		'doo_fc_modality',
		'doo_fc_date_from',
		'doo_fc_date_to',
		'doo_fc_link',
	);

	foreach ( $fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'save_post_doo_accion_formativa', 'doo_accion_formativa_save_meta' );

// =========================================================================
// Seed default Área Temática terms (runs once).
// =========================================================================

/**
 * Insert default taxonomy terms for doo_area_tematica if they don't exist yet.
 * Uses a WP option flag so it only runs once.
 *
 * @return void
 */
function doo_seed_areas_tematicas() {
	if ( get_option( 'doo_areas_tematicas_seeded' ) ) {
		return;
	}

	$defaults = array(
		'Administracion Electronica',
		'Economico - Presupuestaria',
		'Especificos de Determinados Colectivos',
		'Idiomas',
		'Informacion y atencion al publico',
		'Innovacion y Creatividad',
		'Juridico - Procedimental',
		'Nuevas Tecnologias',
		'Politicas de Igualdad',
		'Prevencion y Salud Laboral',
		'Recursos Humanos',
		'Transparencia y Buen Gobierno',
		'Urbanismo y Medio ambiente',
	);

	foreach ( $defaults as $name ) {
		if ( ! term_exists( $name, 'doo_area_tematica' ) ) {
			wp_insert_term( $name, 'doo_area_tematica' );
		}
	}

	update_option( 'doo_areas_tematicas_seeded', true );
}
add_action( 'init', 'doo_seed_areas_tematicas', 20 );

// =========================================================================
// Simplify the Área Temática admin form (name-only).
// =========================================================================

/**
 * Hide description, slug, and parent fields from the Área Temática admin screens.
 *
 * @return void
 */
function doo_area_tematica_admin_simplify() {
	$screen = get_current_screen();
	if ( ! $screen ) {
		return;
	}

	$is_taxonomy_page = ( 'edit-doo_area_tematica' === $screen->id );
	$is_post_page     = ( 'doo_accion_formativa' === $screen->post_type );

	if ( ! $is_taxonomy_page && ! $is_post_page ) {
		return;
	}

	?>
	<style>
		/* Taxonomy list/edit page: hide description, slug, parent columns & fields */
		.term-description-wrap,
		.term-slug-wrap,
		.term-parent-wrap,
		.column-description,
		.column-slug,
		.inline-edit-col label:has(input[name="slug"]),
		.form-field.term-description-wrap,
		.form-field.term-slug-wrap,
		.form-field.term-parent-wrap { display: none !important; }
	</style>
	<?php
}
add_action( 'admin_head', 'doo_area_tematica_admin_simplify' );
