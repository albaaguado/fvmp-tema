<?php
/**
 * Custom Post Type: Jornada (doo_jornada).
 *
 * Registers the CPT and meta fields for Jornadas section.
 * Color theme: teal (#009e96).
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
 * Register the Jornada CPT.
 *
 * @return void
 */
function doo_register_jornada_cpt() {
	register_post_type(
		'doo_jornada',
		array(
			'labels'             => array(
				'name'               => __( 'Jornadas', 'dw-tema' ),
				'singular_name'      => __( 'Jornada', 'dw-tema' ),
				'add_new_item'       => __( 'Añadir jornada', 'dw-tema' ),
				'edit_item'          => __( 'Editar jornada', 'dw-tema' ),
				'all_items'          => __( 'Todas las jornadas', 'dw-tema' ),
				'search_items'       => __( 'Buscar jornadas', 'dw-tema' ),
				'not_found'          => __( 'No se encontraron jornadas', 'dw-tema' ),
				'not_found_in_trash' => __( 'No hay jornadas en la papelera', 'dw-tema' ),
			),
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'show_in_rest'       => true,
			'menu_icon'          => 'dashicons-calendar-alt',
			'menu_position'      => 24,
			'supports'           => array( 'title', 'thumbnail' ),
			'has_archive'        => false,
			'rewrite'            => array( 'slug' => 'jornadas' ),
		)
	);
}
add_action( 'init', 'doo_register_jornada_cpt' );

// =========================================================================
// Register Meta Fields for REST API
// =========================================================================

/**
 * Register jornada meta fields for REST API visibility.
 *
 * @return void
 */
function doo_register_jornada_meta() {
	$meta_fields = array( 'doo_jornada_edition', 'doo_jornada_date', 'doo_jornada_description' );
	
	foreach ( $meta_fields as $field ) {
		register_post_meta(
			'doo_jornada',
			$field,
			array(
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);
	}
}
add_action( 'init', 'doo_register_jornada_meta' );

// =========================================================================
// Classic Editor
// =========================================================================

/**
 * Force classic editor for Jornada CPT.
 *
 * @param bool   $use_block_editor Whether to use block editor.
 * @param string $post_type        Post type.
 * @return bool
 */
function doo_disable_block_editor_for_jornada( $use_block_editor, $post_type ) {
	if ( 'doo_jornada' === $post_type ) {
		return false;
	}
	return $use_block_editor;
}
add_filter( 'use_block_editor_for_post_type', 'doo_disable_block_editor_for_jornada', 10, 2 );

// =========================================================================
// Meta Box
// =========================================================================

/**
 * Register the meta box for Jornada fields.
 *
 * @return void
 */
function doo_jornada_add_meta_box() {
	add_meta_box(
		'doo_jornada_fields',
		__( 'Datos de la jornada', 'dw-tema' ),
		'doo_jornada_meta_box_html',
		'doo_jornada',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'doo_jornada_add_meta_box' );

/**
 * Render the Jornada meta box fields.
 *
 * @param WP_Post $post Current post object.
 * @return void
 */
function doo_jornada_meta_box_html( $post ) {
	wp_nonce_field( 'doo_jornada_save', 'doo_jornada_nonce' );

	$edition     = get_post_meta( $post->ID, 'doo_jornada_edition', true );
	$date        = get_post_meta( $post->ID, 'doo_jornada_date', true );
	$description = get_post_meta( $post->ID, 'doo_jornada_description', true );
	?>
	<div class="doo-jornada-metabox">
		<div class="doo-jornada-metabox__row doo-jornada-metabox__row--two-cols">
			<div class="doo-jornada-metabox__field">
				<label for="doo_jornada_edition">Número de edición</label>
				<input type="text" id="doo_jornada_edition" name="doo_jornada_edition" value="<?php echo esc_attr( $edition ); ?>" placeholder="Ej: I, II, III..." class="widefat" />
				<p class="description">Indica el número de la edición (I, II, III, etc.)</p>
			</div>

			<div class="doo-jornada-metabox__field">
				<label for="doo_jornada_date">Fecha de la jornada</label>
				<input type="text" id="doo_jornada_date" name="doo_jornada_date" value="<?php echo esc_attr( $date ); ?>" placeholder="Ej: Septiembre 2024" class="widefat" />
				<p class="description">Formato libre: "Septiembre 2024", "26 de marzo de 2025", etc.</p>
			</div>
		</div>

		<div class="doo-jornada-metabox__row">
			<div class="doo-jornada-metabox__field">
				<label for="doo_jornada_description">Descripción corta</label>
				<input type="text" id="doo_jornada_description" name="doo_jornada_description" value="<?php echo esc_attr( $description ); ?>" placeholder="Ej: I Jornadas de innovación en la administración local." class="widefat" />
			</div>
		</div>

		<div class="doo-jornada-metabox__info-box">
			<p><strong>📷 Recuerda:</strong> Establece la imagen destacada en la barra lateral derecha →</p>
		</div>

		<?php
		$linked_page_id = get_post_meta( $post->ID, 'doo_linked_page_id', true );
		if ( $linked_page_id ) :
			$page_url = get_permalink( $linked_page_id );
			?>
			<div class="doo-jornada-metabox__row doo-jornada-metabox__info">
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
 * Save Jornada meta fields on post save.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function doo_jornada_save_meta( $post_id ) {
	if ( ! isset( $_POST['doo_jornada_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['doo_jornada_nonce'] ) ), 'doo_jornada_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array( 'doo_jornada_edition', 'doo_jornada_date', 'doo_jornada_description' );
	foreach ( $fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'save_post_doo_jornada', 'doo_jornada_save_meta' );

// =========================================================================
// Auto-create Page when Jornada is published.
// =========================================================================

/**
 * Automatically create a WordPress Page when a Jornada is published.
 *
 * @param int     $post_id Post ID.
 * @param WP_Post $post    Post object.
 * @param bool    $update  Whether this is an update.
 * @return void
 */
function doo_create_page_for_jornada( $post_id, $post, $update ) {
	if ( 'doo_jornada' !== $post->post_type ) {
		return;
	}
	if ( 'publish' !== $post->post_status ) {
		return;
	}
	if ( defined( 'DOO_JORNADA_CREATING_PAGE' ) && DOO_JORNADA_CREATING_PAGE ) {
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

	define( 'DOO_JORNADA_CREATING_PAGE', true );

	// Nest under /jornadas/ parent page if it exists
	$parent_page = get_page_by_path( 'jornadas', OBJECT, 'page' );
	$parent_id   = $parent_page ? $parent_page->ID : 0;

	$page_id = wp_insert_post(
		array(
			'post_title'   => $post->post_title,
			'post_name'    => $post->post_name,
			'post_type'    => 'page',
			'post_status'  => 'publish',
			'post_parent'  => $parent_id,
			'post_content' => '<!-- wp:doo/jornada-detail /-->',
		)
	);

	if ( ! is_wp_error( $page_id ) && $page_id > 0 ) {
		update_post_meta( $post_id, 'doo_linked_page_id', $page_id );
		update_post_meta( $page_id, 'doo_linked_cpt_id', $post_id );
		// Set the page template
		update_post_meta( $page_id, '_wp_page_template', 'single-doo_accion_formativa.html' );
	}
}
add_action( 'wp_after_insert_post', 'doo_create_page_for_jornada', 10, 3 );

// =========================================================================
// Admin UX
// =========================================================================

/**
 * Admin styles for Jornada screens.
 *
 * @return void
 */
function doo_jornada_admin_styles() {
	$screen = get_current_screen();
	if ( ! $screen || 'doo_jornada' !== $screen->post_type ) {
		return;
	}
	?>
	<style>
		.doo-jornada-metabox {
			background: #f9fafb;
			border-radius: 8px;
			padding: 24px;
			margin: -6px -12px -12px;
		}
		
		.doo-jornada-metabox__row {
			margin-bottom: 20px;
		}
		
		.doo-jornada-metabox__row--two-cols {
			display: grid;
			grid-template-columns: 1fr 1fr;
			gap: 16px;
		}
		
		.doo-jornada-metabox__field label {
			display: block;
			font-weight: 600;
			margin-bottom: 8px;
			color: #1d2939;
			font-size: 14px;
		}
		
		.doo-jornada-metabox__field input[type="text"] {
			width: 100%;
			padding: 10px 12px;
			border: 1px solid #d0d5dd;
			border-radius: 6px;
			font-size: 14px;
			transition: border-color 0.2s;
		}
		
		.doo-jornada-metabox__field input[type="text"]:focus {
			border-color: #009e96;
			outline: none;
			box-shadow: 0 0 0 3px rgba(0, 158, 150, 0.1);
		}
		
		.doo-jornada-metabox__info-box {
			background: #e0f5f4;
			border: 1px solid #009e96;
			padding: 12px 16px;
			border-radius: 6px;
			margin-top: 8px;
		}
		
		.doo-jornada-metabox__info-box p {
			margin: 0;
			font-size: 13px;
			color: #0d6663;
		}
		
		.doo-jornada-metabox__info {
			background: #e0f5f4;
			padding: 16px;
			border-radius: 6px;
			border-left: 4px solid #009e96;
			margin-top: 16px;
		}
		
		.doo-jornada-metabox__info p {
			margin: 0;
			font-size: 14px;
			color: #1a2b4a;
		}
		
		.doo-jornada-metabox__info a {
			color: #009e96;
			text-decoration: none;
			font-weight: 500;
		}
		
		.doo-jornada-metabox__info a:hover {
			text-decoration: underline;
		}
	</style>
	<?php
}
add_action( 'admin_head', 'doo_jornada_admin_styles' );

/**
 * Clean up unnecessary metaboxes for Jornada.
 */
function doo_jornada_cleanup_metaboxes() {
	remove_meta_box( 'slugdiv', 'doo_jornada', 'normal' );
	remove_meta_box( 'postexcerpt', 'doo_jornada', 'normal' );
	remove_meta_box( 'postcustom', 'doo_jornada', 'normal' );
}
add_action( 'admin_menu', 'doo_jornada_cleanup_metaboxes' );
