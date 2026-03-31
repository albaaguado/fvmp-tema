<?php
/**
 * CPT Docente — fichas del profesorado (solo se editan aquí, no en el bloque).
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register the Docente CPT.
 *
 * @return void
 */
function doo_register_docente_cpt() {
	register_post_type(
		'doo_docente',
		array(
			'labels'       => array(
				'name'          => __( 'Docentes', 'dw-tema' ),
				'singular_name' => __( 'Docente', 'dw-tema' ),
				'add_new_item'  => __( 'Añadir docente', 'dw-tema' ),
				'edit_item'     => __( 'Editar docente', 'dw-tema' ),
				'all_items'     => __( 'Todos los docentes', 'dw-tema' ),
				'search_items'  => __( 'Buscar docentes', 'dw-tema' ),
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'menu_icon'     => 'dashicons-groups',
			'menu_position' => 20,
			'supports'      => array( 'title', 'thumbnail', 'page-attributes' ),
			'has_archive'   => false,
		)
	);
}
add_action( 'init', 'doo_register_docente_cpt' );

/**
 * Register the meta box for Docente fields.
 *
 * @return void
 */
function doo_docente_add_meta_box() {
	add_meta_box(
		'doo_docente_fields',
		__( 'Datos del docente', 'dw-tema' ),
		'doo_docente_meta_box_html',
		'doo_docente',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'doo_docente_add_meta_box' );

/**
 * Render the Docente meta box fields.
 *
 * @param WP_Post $post Current post object.
 * @return void
 */
function doo_docente_meta_box_html( $post ) {
	wp_nonce_field( 'doo_docente_save', 'doo_docente_nonce' );

	$fields = array(
		'doo_degree'    => 'Titulación',
		'doo_role'      => 'Cargo',
		'doo_role2'     => 'Cargo 2 (opcional)',
		'doo_org'       => 'Organización',
		'doo_linkedin'  => 'URL LinkedIn',
		'doo_twitter'   => 'URL Twitter',
		'doo_instagram' => 'URL Instagram',
		'doo_facebook'  => 'URL Facebook',
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
 * Save Docente meta fields on post save.
 *
 * @param int $post_id Post ID.
 * @return void
 */
function doo_docente_save_meta( $post_id ) {
	if ( ! isset( $_POST['doo_docente_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['doo_docente_nonce'] ) ), 'doo_docente_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$fields = array( 'doo_degree', 'doo_role', 'doo_role2', 'doo_org', 'doo_linkedin', 'doo_twitter', 'doo_instagram', 'doo_facebook' );

	foreach ( $fields as $key ) {
		if ( isset( $_POST[ $key ] ) ) {
			update_post_meta( $post_id, $key, sanitize_text_field( wp_unslash( $_POST[ $key ] ) ) );
		}
	}
}
add_action( 'save_post_doo_docente', 'doo_docente_save_meta' );
