<?php
/**
 * Custom Post Type: Acción FAP (doo_accion_fap).
 *
 * Registers the CPT, taxonomies, and meta fields
 * for the Formación para Equipos de Atención Primaria (FAP) section.
 * Color theme: orange (#f5a623).
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// =========================================================================
// CPT Registration
// =========================================================================

/**
 * Register the Acción FAP CPT.
 *
 * @return void
 */
function doo_register_accion_fap_cpt() {
	register_post_type(
		'doo_accion_fap',
		array(
			'labels'             => array(
				'name'               => __( 'Acciones FAP', 'dw-tema' ),
				'singular_name'      => __( 'Acción FAP', 'dw-tema' ),
				'add_new_item'       => __( 'Añadir acción FAP', 'dw-tema' ),
				'edit_item'          => __( 'Editar acción FAP', 'dw-tema' ),
				'all_items'          => __( 'Todas las acciones FAP', 'dw-tema' ),
				'search_items'       => __( 'Buscar acciones FAP', 'dw-tema' ),
				'not_found'          => __( 'No se encontraron acciones FAP', 'dw-tema' ),
				'not_found_in_trash' => __( 'No hay acciones FAP en la papelera', 'dw-tema' ),
			),
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-clipboard',
			'menu_position'      => 22,
			'supports'           => array( 'title' ),
			'has_archive'        => false,
			'rewrite'            => array( 'slug' => 'fap' ),
		)
	);
}
add_action( 'init', 'doo_register_accion_fap_cpt' );

// =========================================================================
// Taxonomy: Área FAP
// =========================================================================

/**
 * Register the Área FAP taxonomy.
 *
 * @return void
 */
function doo_register_fap_area_taxonomy() {
	register_taxonomy(
		'doo_fap_area',
		'doo_accion_fap',
		array(
			'labels'            => array(
				'name'          => __( 'Áreas FAP', 'dw-tema' ),
				'singular_name' => __( 'Área FAP', 'dw-tema' ),
				'add_new_item'  => __( 'Añadir área FAP', 'dw-tema' ),
				'all_items'     => __( 'Todas las áreas FAP', 'dw-tema' ),
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_in_rest'      => true,
			'rewrite'           => false,
			'show_admin_column' => true,
		)
	);
}
add_action( 'init', 'doo_register_fap_area_taxonomy' );

/**
 * Attach the shared doo_modalidad taxonomy to the FAP CPT.
 * Runs at priority 15, after doo_modalidad is registered at priority 10.
 *
 * @return void
 */
function doo_fap_attach_modalidad() {
	register_taxonomy_for_object_type( 'doo_modalidad', 'doo_accion_fap' );
}
add_action( 'init', 'doo_fap_attach_modalidad', 15 );

// =========================================================================
// Classic Editor
// =========================================================================

/**
 * Force classic editor for FAP CPT.
 *
 * @param bool   $use_block_editor Whether to use block editor.
 * @param string $post_type        Post type slug.
 * @return bool
 */
function doo_disable_block_editor_for_accion_fap( $use_block_editor, $post_type ) {
	if ( 'doo_accion_fap' === $post_type ) {
		return false;
	}
	return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'doo_disable_block_editor_for_accion_fap', 10, 2 );

// =========================================================================
// Meta Box
// =========================================================================

/**
 * Register the meta box for Acción FAP fields.
 *
 * @return void
 */
function doo_accion_fap_add_meta_box() {
	add_meta_box(
		'doo_accion_fap_fields',
		__( 'Datos de la acción FAP', 'dw-tema' ),
		'doo_accion_fap_meta_box_html',
		'doo_accion_fap',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'doo_accion_fap_add_meta_box' );

/**
 * Render the Acción FAP meta box fields.
 *
 * @param WP_Post $post Current post object.
 * @return void
 */
function doo_accion_fap_meta_box_html( $post ) {
	wp_nonce_field( 'doo_accion_fap_save', 'doo_accion_fap_nonce' );

	$code  = get_post_meta( $post->ID, 'doo_fc_code', true );
	$hours = get_post_meta( $post->ID, 'doo_fc_hours', true );
	$seats = get_post_meta( $post->ID, 'doo_fc_seats', true );
	$dates = get_post_meta( $post->ID, 'doo_fc_dates', true );
	?>
	<div class="doo-fc-metabox">
		<div class="doo-fc-metabox__row">
			<div class="doo-fc-metabox__field">
				<label for="doo_fc_code">Código del curso</label>
				<input type="text" id="doo_fc_code" name="doo_fc_code" value="<?php echo esc_attr( $code ); ?>" placeholder="Ej: FAP01" class="widefat" />
			</div>
		</div>

		<div class="doo-fc-metabox__row doo-fc-metabox__row--two-cols">
			<div class="doo-fc-metabox__field">
				<label for="doo_fc_hours">Duración (horas)</label>
				<input type="number" id="doo_fc_hours" name="doo_fc_hours" value="<?php echo esc_attr( $hours ); ?>" placeholder="Ej: 30" class="widefat" min="1" />
			</div>
			<div class="doo-fc-metabox__field">
				<label for="doo_fc_seats">Plazas</label>
				<input type="number" id="doo_fc_seats" name="doo_fc_seats" value="<?php echo esc_attr( $seats ); ?>" placeholder="Ej: 50" class="widefat" min="1" />
			</div>
		</div>

		<div class="doo-fc-metabox__row">
			<div class="doo-fc-metabox__field">
				<label for="doo_fc_dates">Fechas de impartición</label>
				<input type="text" id="doo_fc_dates" name="doo_fc_dates" value="<?php echo esc_attr( $dates ); ?>" placeholder="Ej: 31 de marzo, 1, 7 y 8 de abril de 2025" class="widefat" />
				<p class="description">Escribe las fechas tal y como quieres que aparezcan: días sueltos, rangos, o cualquier combinación.</p>
			</div>
		</div>

		<div class="doo-fc-metabox__info-box">
			<p><strong>📌 Recuerda:</strong> Selecciona el Área FAP y la Modalidad en la barra lateral derecha →</p>
		</div>

		<?php
		$linked_page_id = get_post_meta( $post->ID, 'doo_linked_page_id', true );
		if ( $linked_page_id ) :
			$page_url = get_permalink( $linked_page_id );
			?>
			<div class="doo-fc-metabox__row doo-fc-metabox__info">
				<p>
					<strong>📄 Página vinculada:</strong>
					<a href="<?php echo esc_url( get_edit_post_link( $linked_page_id ) ); ?>" target="_blank">Editar página de detalles</a>
					|
					<a href="<?php echo esc_url( $page_url ); ?>" target="_blank">Ver en el sitio</a>
				</p>
			</div>
		<?php endif; ?>
	</div>
	<?php
}

/**
 * Save Acción FAP meta fields on post save.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function doo_accion_fap_save_meta( $post_id ) {
	if ( ! isset( $_POST['doo_accion_fap_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['doo_accion_fap_nonce'] ) ), 'doo_accion_fap_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array( 'doo_fc_code', 'doo_fc_hours', 'doo_fc_seats', 'doo_fc_dates' );
	foreach ( $fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'save_post_doo_accion_fap', 'doo_accion_fap_save_meta' );

// =========================================================================
// Admin UX
// =========================================================================

/**
 * Admin styles and field cleanup for FAP screens.
 *
 * @return void
 */
function doo_fap_admin_simplify() {
	$screen = get_current_screen();
	if ( ! $screen ) {
		return;
	}

	$relevant = ( 'edit-doo_fap_area' === $screen->id || 'doo_accion_fap' === $screen->post_type );
	if ( ! $relevant ) {
		return;
	}
	?>
	<style>
		.term-description-wrap, .term-slug-wrap, .term-parent-wrap,
		.column-description, .column-slug,
		.inline-edit-col label:has(input[name="slug"]),
		.form-field.term-description-wrap,
		.form-field.term-slug-wrap,
		.form-field.term-parent-wrap { display: none !important; }

		#doo_modalidaddiv .category-add { display: none !important; }

		.doo-fc-metabox { background: #f9fafb; border-radius: 8px; padding: 24px; margin: -6px -12px -12px; }
		.doo-fc-metabox__row { margin-bottom: 20px; }
		.doo-fc-metabox__row--two-cols { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
		.doo-fc-metabox__field label { display: block; font-weight: 600; margin-bottom: 8px; color: #1d2939; font-size: 14px; }
		.doo-fc-metabox__field input[type="text"],
		.doo-fc-metabox__field input[type="number"] { width: 100%; padding: 10px 12px; border: 1px solid #d0d5dd; border-radius: 6px; font-size: 14px; transition: border-color 0.2s; }
		.doo-fc-metabox__field input:focus { border-color: #f5a623; outline: none; box-shadow: 0 0 0 3px rgba(245, 166, 35, 0.1); }
		.doo-fc-metabox__info-box { background: #fffbeb; border: 1px solid #fde68a; padding: 12px 16px; border-radius: 6px; margin-top: 8px; }
		.doo-fc-metabox__info-box p { margin: 0; font-size: 13px; color: #92400e; }
		.doo-fc-metabox__info { background: #fff4e5; padding: 16px; border-radius: 6px; border-left: 4px solid #f5a623; }
		.doo-fc-metabox__info p { margin: 0; font-size: 14px; color: #1a2b4a; }
		.doo-fc-metabox__info a { color: #f5a623; text-decoration: none; font-weight: 500; }
		.doo-fc-metabox__info a:hover { text-decoration: underline; }
	</style>
	<?php
}
add_action( 'admin_head', 'doo_fap_admin_simplify' );

/**
 * Remove unnecessary metaboxes from FAP CPT edit screen.
 *
 * @return void
 */
function doo_accion_fap_cleanup_metaboxes() {
	remove_meta_box( 'slugdiv',      'doo_accion_fap', 'normal' );
	remove_meta_box( 'postexcerpt',  'doo_accion_fap', 'normal' );
	remove_meta_box( 'postcustom',   'doo_accion_fap', 'normal' );
}

// =========================================================================
// Seed default Área FAP terms (runs once).
// =========================================================================

/**
 * Insert default taxonomy terms for doo_fap_area if they don't exist yet.
 *
 * @return void
 */
function doo_seed_fap_areas() {
	if ( get_option( 'doo_fap_areas_seeded' ) ) {
		return;
	}

	$defaults = array(
		'Atención Primaria',
		'Diversidad',
		'Familia, infancia, adolescencia',
		'Inclusión, Cooperación',
		'Mujer. Violencia de género',
		'Personas con discapacidad',
		'Personas mayores. Dependencia',
		'Salud mental',
	);

	foreach ( $defaults as $name ) {
		if ( ! term_exists( $name, 'doo_fap_area' ) ) {
			wp_insert_term( $name, 'doo_fap_area' );
		}
	}

	update_option( 'doo_fap_areas_seeded', true );
}
add_action( 'init', 'doo_seed_fap_areas', 20 );
add_action( 'admin_menu', 'doo_accion_fap_cleanup_metaboxes' );

// =========================================================================
// Auto-create Page when FAP action is published.
// =========================================================================

/**
 * Automatically create a WordPress Page when a FAP Acción is published.
 * The page uses the block doo/af-curso pre-configured with FAP orange colors.
 * The page is nested under the /fap/ parent page (if it exists) for admin organisation.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an update.
 * @return void
 */
function doo_create_page_for_accion_fap( $post_id, $post, $update ) {
	if ( 'doo_accion_fap' !== $post->post_type ) {
		return;
	}
	if ( 'publish' !== $post->post_status ) {
		return;
	}
	if ( defined( 'DOO_FAP_CREATING_PAGE' ) && DOO_FAP_CREATING_PAGE ) {
		return;
	}

	$linked_page_id = get_post_meta( $post_id, 'doo_linked_page_id', true );
	if ( $linked_page_id && 'publish' === get_post_status( $linked_page_id ) ) {
		wp_update_post(
			array(
				'ID'         => $linked_page_id,
				'post_title' => $post->post_title,
			)
		);
		return;
	}

	define( 'DOO_FAP_CREATING_PAGE', true );

	// Nest under /fap/ parent page if it exists
	$parent_page = get_page_by_path( 'fap', OBJECT, 'page' );
	$parent_id   = $parent_page ? $parent_page->ID : 0;

	$block_attrs = wp_json_encode(
		array(
			'section'      => 'fap',
			'accentColor'  => '#f5a623',
			'accentBg'     => '#fff4e5',
			'navyColor'    => '#1e3a5f',
			'borderColor'  => '#f5a623',
			'sectionLabel' => 'Formación para Equipos de Atención Primaria',
			'sectionSlug'  => 'fap',
		),
		JSON_UNESCAPED_SLASHES
	);

	$page_id = wp_insert_post(
		array(
			'post_title'   => $post->post_title,
			'post_name'    => $post->post_name,
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_parent'  => $parent_id,
			'post_content' => '<!-- wp:doo/af-curso ' . $block_attrs . ' /-->',
		)
	);

	if ( ! is_wp_error( $page_id ) && $page_id > 0 ) {
		update_post_meta( $post_id, 'doo_linked_page_id', $page_id );
		update_post_meta( $page_id, 'doo_linked_cpt_id', $post_id );
		// Set the page template
		update_post_meta( $page_id, '_wp_page_template', 'single-doo_accion_formativa.html' );
	}
}
add_action( 'wp_after_insert_post', 'doo_create_page_for_accion_fap', 10, 3 );

// =========================================================================
// Seed FAP landing/listado page (runs once).
// =========================================================================

/**
 * Create the FAP section listing page if it doesn't exist yet.
 * Uses the doo/af-courses block configured for FAP.
 *
 * @return void
 */
function doo_seed_fap_landing_page() {
	if ( get_option( 'doo_fap_landing_seeded' ) ) {
		return;
	}

	// Don't run until WP is fully loaded
	if ( ! did_action( 'wp_loaded' ) ) {
		return;
	}

	$existing = get_page_by_path( 'fap', OBJECT, 'page' );
	if ( $existing ) {
		update_option( 'doo_fap_landing_seeded', true );
		return;
	}

	$page_id = wp_insert_post( array(
		'post_title'   => 'FAP',
		'post_name'    => 'fap',
		'post_type'    => 'page',
		'post_status'  => 'publish',
		'post_content' => '<!-- wp:doo/af-courses {"postType":"doo_accion_fap"} /-->',
	) );

	if ( ! is_wp_error( $page_id ) && $page_id > 0 ) {
		update_option( 'doo_fap_landing_seeded', true );
	}
}
add_action( 'wp_loaded', 'doo_seed_fap_landing_page' );
