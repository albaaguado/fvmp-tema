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
				'name'               => __( 'Acciones FC', 'dw-tema' ),
				'singular_name'      => __( 'Acción FC', 'dw-tema' ),
				'add_new_item'       => __( 'Añadir acción FC', 'dw-tema' ),
				'edit_item'          => __( 'Editar acción FC', 'dw-tema' ),
				'all_items'          => __( 'Todas las acciones FC', 'dw-tema' ),
				'search_items'       => __( 'Buscar acciones FC', 'dw-tema' ),
				'not_found'          => __( 'No se encontraron acciones FC', 'dw-tema' ),
				'not_found_in_trash' => __( 'No hay acciones FC en la papelera', 'dw-tema' ),
			),
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-welcome-learn-more',
			'menu_position'      => 21,
			'supports'           => array( 'title' ),
			'has_archive'        => false,
			'rewrite'            => array( 'slug' => 'formacion-continua' ),
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
 * Register the Modalidad taxonomy for Acción Formativa.
 *
 * @return void
 */
function doo_register_modalidad_taxonomy() {
	register_taxonomy(
		'doo_modalidad',
		'doo_accion_formativa',
		array(
			'labels'       => array(
				'name'          => __( 'Modalidades', 'dw-tema' ),
				'singular_name' => __( 'Modalidad', 'dw-tema' ),
				'all_items'     => __( 'Todas las modalidades', 'dw-tema' ),
			),
			'hierarchical'      => true,
			'show_ui'           => true,
			'show_in_menu'      => false,
			'show_in_rest'      => true,
			'rewrite'           => false,
			'show_admin_column' => true,
		)
	);
}
add_action( 'init', 'doo_register_modalidad_taxonomy' );

/**
 * Usar el editor clásico para la Acción formativa, de forma que el metabox
 * con los datos (código, duración, plazas, fechas...) sea el formulario
 * principal en pantalla.
 */
function doo_disable_block_editor_for_accion_formativa( $use_block_editor, $post_type ) {
	if ( 'doo_accion_formativa' === $post_type ) {
		return false;
	}
	return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'doo_disable_block_editor_for_accion_formativa', 10, 2 );

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

	$code  = get_post_meta( $post->ID, 'doo_fc_code', true );
	$hours = get_post_meta( $post->ID, 'doo_fc_hours', true );
	$seats = get_post_meta( $post->ID, 'doo_fc_seats', true );
	$dates = get_post_meta( $post->ID, 'doo_fc_dates', true );
	?>
	<div class="doo-fc-metabox">
		<div class="doo-fc-metabox__row">
			<div class="doo-fc-metabox__field">
				<label for="doo_fc_code">Código del curso</label>
				<input type="text" id="doo_fc_code" name="doo_fc_code" value="<?php echo esc_attr( $code ); ?>" placeholder="Ej: AE01.1" class="widefat" />
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
			<p><strong>📌 Recuerda:</strong> Selecciona el Área Temática y la Modalidad en la barra lateral derecha →</p>
		</div>

		<?php
		$linked_page_id = get_post_meta( $post->ID, 'doo_linked_page_id', true );
		if ( $linked_page_id ) :
			$page_url = get_permalink( $linked_page_id );
			?>
			<div class="doo-fc-metabox__row doo-fc-metabox__info">
				<p>
					<strong>📄 Página vinculada:</strong> 
					<a href="<?php echo esc_url( get_edit_post_link( $linked_page_id ) ); ?>" target="_blank">
						Editar página de detalles
					</a>
					|
					<a href="<?php echo esc_url( $page_url ); ?>" target="_blank">
						Ver en el sitio
					</a>
				</p>
			</div>
		<?php endif; ?>
	</div>
	<?php
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
		'doo_fc_dates',
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
// Seed default Modalidad terms (runs once).
// =========================================================================

/**
 * Insert default taxonomy terms for doo_modalidad if they don't exist yet.
 * Uses a WP option flag so it only runs once.
 *
 * @return void
 */
function doo_seed_modalidades() {
	if ( get_option( 'doo_modalidades_seeded' ) ) {
		return;
	}

	$defaults = array(
		'Online',
		'Presencial',
		'Streaming',
		'Semipresencial',
		'Mix Online - Presencial',
		'Mix Online - Streaming',
	);

	foreach ( $defaults as $name ) {
		if ( ! term_exists( $name, 'doo_modalidad' ) ) {
			wp_insert_term( $name, 'doo_modalidad' );
		}
	}

	update_option( 'doo_modalidades_seeded', true );
}
add_action( 'init', 'doo_seed_modalidades', 20 );

// =========================================================================
// Auto-create Page when Acción Formativa is published.
// =========================================================================

/**
 * Automatically create a WordPress Page when an Acción Formativa is published.
 * The page uses the same slug and title, and contains the doo/af-curso block.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an update or new post.
 * @return void
 */
function doo_create_page_for_accion_formativa( $post_id, $post, $update ) {
	// Only run for doo_accion_formativa posts
	if ( 'doo_accion_formativa' !== $post->post_type ) {
		return;
	}

	// Only run when status changes to 'publish'
	if ( 'publish' !== $post->post_status ) {
		return;
	}

	// Avoid infinite loops
	if ( defined( 'DOO_CREATING_PAGE' ) && DOO_CREATING_PAGE ) {
		return;
	}

	// Check if page already exists (stored in post meta)
	$linked_page_id = get_post_meta( $post_id, 'doo_linked_page_id', true );
	
	if ( $linked_page_id && 'publish' === get_post_status( $linked_page_id ) ) {
		// Page already exists, optionally update its title
		wp_update_post(
			array(
				'ID'         => $linked_page_id,
				'post_title' => $post->post_title,
			)
		);
		return;
	}

	// Define constant to avoid recursion
	define( 'DOO_CREATING_PAGE', true );

	// Create the page with the doo/af-curso block
	$page_id = wp_insert_post(
		array(
			'post_title'   => $post->post_title,
			'post_name'    => $post->post_name, // Same slug
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_content' => '<!-- wp:doo/af-curso /-->',
		)
	);

	if ( ! is_wp_error( $page_id ) && $page_id > 0 ) {
		// Store the link between the CPT and the Page
		update_post_meta( $post_id, 'doo_linked_page_id', $page_id );
		update_post_meta( $page_id, 'doo_linked_cpt_id', $post_id );
		// Set the page template
		update_post_meta( $page_id, '_wp_page_template', 'single-doo_accion_formativa.html' );
	}
}
add_action( 'wp_after_insert_post', 'doo_create_page_for_accion_formativa', 10, 3 );

// =========================================================================
// Date conversion helpers.
// =========================================================================

/**
 * Convert date from dd/mm/yyyy to YYYY-MM-DD for date input.
 *
 * @param string $date Date in dd/mm/yyyy format.
 * @return string Date in YYYY-MM-DD format or empty string.
 */
function doo_convert_dmy_to_ymd( $date ) {
	if ( ! $date ) {
		return '';
	}
	
	if ( preg_match( '#^(\d{1,2})/(\d{1,2})/(\d{4})$#', $date, $m ) ) {
		$day   = str_pad( $m[1], 2, '0', STR_PAD_LEFT );
		$month = str_pad( $m[2], 2, '0', STR_PAD_LEFT );
		$year  = $m[3];
		return "$year-$month-$day";
	}
	
	return $date;
}

/**
 * Convert date from YYYY-MM-DD to dd/mm/yyyy for storage.
 *
 * @param string $date Date in YYYY-MM-DD format.
 * @return string Date in dd/mm/yyyy format or empty string.
 */
function doo_convert_ymd_to_dmy( $date ) {
	if ( ! $date ) {
		return '';
	}
	
	if ( preg_match( '#^(\d{4})-(\d{1,2})-(\d{1,2})$#', $date, $m ) ) {
		$year  = $m[1];
		$month = intval( $m[2] );
		$day   = intval( $m[3] );
		return "$day/$month/$year";
	}
	
	return $date;
}

// =========================================================================
// Date formatting helper.
// =========================================================================

/**
 * Format date range from dd/mm/aaaa inputs to "24 may - 24 jun" output.
 *
 * @param string $date_from Date in dd/mm/aaaa format.
 * @param string $date_to   Date in dd/mm/aaaa format.
 * @return string Formatted date range.
 */
function doo_format_date_range( $date_from, $date_to ) {
	if ( ! $date_from && ! $date_to ) {
		return '';
	}

	$months_es = array(
		1  => 'ene',
		2  => 'feb',
		3  => 'mar',
		4  => 'abr',
		5  => 'may',
		6  => 'jun',
		7  => 'jul',
		8  => 'ago',
		9  => 'sep',
		10 => 'oct',
		11 => 'nov',
		12 => 'dic',
	);

	$format_date = function ( $date_str ) use ( $months_es ) {
		if ( ! $date_str ) {
			return '';
		}
		
		// Try dd/mm/aaaa format
		if ( preg_match( '#^(\d{1,2})/(\d{1,2})/(\d{4})$#', $date_str, $m ) ) {
			$day   = (int) $m[1];
			$month = (int) $m[2];
			$year  = (int) $m[3];
			
			if ( isset( $months_es[ $month ] ) ) {
				return $day . ' ' . $months_es[ $month ];
			}
		}
		
		return $date_str; // Return as-is if format doesn't match
	};

	$from_formatted = $format_date( $date_from );
	$to_formatted   = $format_date( $date_to );

	if ( $from_formatted && $to_formatted ) {
		return $from_formatted . ' - ' . $to_formatted;
	}

	return $from_formatted ?: $to_formatted;
}

// =========================================================================
// Simplify the Área Temática admin form (name-only).
// =========================================================================

/**
 * Hide description, slug, and parent fields from taxonomy admin screens.
 *
 * @return void
 */
function doo_area_tematica_admin_simplify() {
	$screen = get_current_screen();
	if ( ! $screen ) {
		return;
	}

	$is_taxonomy_page = ( 'edit-doo_area_tematica' === $screen->id || 'edit-doo_modalidad' === $screen->id );
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

		/* En la pantalla de taxonomía de Modalidad no se pueden crear nuevas */
		.taxonomy-doo_modalidad .form-wrap,
		.taxonomy-doo_modalidad .page-title-action { display: none !important; }

		/* En el metabox lateral de Modalidad del CPT ocultamos el bloque de "añadir" */
		#doo_modalidaddiv .category-add { display: none !important; }

		/* Metabox styling for Acción Formativa */
		.doo-fc-metabox {
			background: #f9fafb;
			border-radius: 8px;
			padding: 24px;
			margin: -6px -12px -12px;
		}
		
		.doo-fc-metabox__row {
			margin-bottom: 20px;
		}
		
		.doo-fc-metabox__row--two-cols {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 16px;
		}
		
		.doo-fc-metabox__field label {
			display: block;
			font-weight: 600;
			margin-bottom: 8px;
			color: #1d2939;
			font-size: 14px;
		}
		
		.doo-fc-metabox__field input[type="text"],
		.doo-fc-metabox__field input[type="date"] {
			width: 100%;
			padding: 10px 12px;
			border: 1px solid #d0d5dd;
			border-radius: 6px;
			font-size: 14px;
			transition: border-color 0.2s;
		}
		
		.doo-fc-metabox__field input[type="text"]:focus,
		.doo-fc-metabox__field input[type="date"]:focus {
			border-color: #009e96;
			outline: none;
			box-shadow: 0 0 0 3px rgba(0, 158, 150, 0.1);
		}
		
		.doo-fc-metabox__info-box {
			background: #fffbeb;
			border: 1px solid #fde68a;
			padding: 12px 16px;
			border-radius: 6px;
			margin-top: 8px;
		}
		
		.doo-fc-metabox__info-box p {
			margin: 0;
			font-size: 13px;
			color: #92400e;
		}
		
		.doo-fc-metabox__info {
			background: #e0f5f4;
			padding: 16px;
			border-radius: 6px;
			border-left: 4px solid #009e96;
		}
		
		.doo-fc-metabox__info p {
			margin: 0;
			font-size: 14px;
			color: #1a2b4a;
		}
		
		.doo-fc-metabox__info a {
			color: #009e96;
			text-decoration: none;
			font-weight: 500;
		}
		
		.doo-fc-metabox__info a:hover {
			text-decoration: underline;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'doo_area_tematica_admin_simplify' );

/**
 * Limpiar metaboxes innecesarios en Acción formativa (slug, campos extra).
 */
function doo_accion_formativa_cleanup_metaboxes() {
	remove_meta_box( 'slugdiv', 'doo_accion_formativa', 'normal' );
	remove_meta_box( 'postexcerpt', 'doo_accion_formativa', 'normal' );
	remove_meta_box( 'postcustom', 'doo_accion_formativa', 'normal' );
}
add_action( 'admin_menu', 'doo_accion_formativa_cleanup_metaboxes' );
