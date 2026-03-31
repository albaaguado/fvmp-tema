<?php
/**
 * CPT Testimonio — citas del alumnado (solo se editan aquí, no en el bloque).
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Meta key for card accent color.
 *
 * @return string
 */
function doo_testimonio_color_meta_key() {
	return 'doo_testimonio_color';
}

/**
 * Allowed card colors (matches SCSS modifiers).
 *
 * @return string[]
 */
function doo_testimonio_allowed_colors() {
	return array( 'teal', 'orange', 'navy' );
}

/**
 * Register the Testimonio CPT.
 *
 * @return void
 */
function doo_register_testimonio_cpt() {
	register_post_type(
		'doo_testimonio',
		array(
			'labels'       => array(
				'name'          => __( 'Testimonios', 'dw-tema' ),
				'singular_name' => __( 'Testimonio', 'dw-tema' ),
				'add_new_item'  => __( 'Añadir testimonio', 'dw-tema' ),
				'edit_item'     => __( 'Editar testimonio', 'dw-tema' ),
				'all_items'     => __( 'Todos los testimonios', 'dw-tema' ),
				'search_items'  => __( 'Buscar testimonios', 'dw-tema' ),
			),
			'public'        => false,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'menu_icon'     => 'dashicons-format-quote',
			'menu_position' => 21,
			'supports'      => array( 'title', 'editor', 'page-attributes' ),
			'has_archive'   => false,
		)
	);
}
add_action( 'init', 'doo_register_testimonio_cpt' );

/**
 * Meta box: color de acento de la tarjeta.
 *
 * @return void
 */
function doo_testimonio_add_meta_box() {
	add_meta_box(
		'doo_testimonio_color',
		__( 'Apariencia', 'dw-tema' ),
		'doo_testimonio_color_meta_box_html',
		'doo_testimonio',
		'side',
		'default'
	);
}
add_action( 'add_meta_boxes', 'doo_testimonio_add_meta_box' );

/**
 * @param WP_Post $post Post.
 * @return void
 */
function doo_testimonio_color_meta_box_html( $post ) {
	wp_nonce_field( 'doo_testimonio_save', 'doo_testimonio_nonce' );

	$current = get_post_meta( $post->ID, doo_testimonio_color_meta_key(), true );
	if ( ! $current || ! in_array( $current, doo_testimonio_allowed_colors(), true ) ) {
		$current = 'teal';
	}

	echo '<p><label for="doo_testimonio_color_field">' . esc_html__( 'Color del bloque', 'dw-tema' ) . '</label></p>';
	echo '<select name="' . esc_attr( doo_testimonio_color_meta_key() ) . '" id="doo_testimonio_color_field" style="width:100%;">';
	$labels = array(
		'teal'   => __( 'Verde azulado (teal)', 'dw-tema' ),
		'orange' => __( 'Naranja', 'dw-tema' ),
		'navy'   => __( 'Azul marino', 'dw-tema' ),
	);
	foreach ( doo_testimonio_allowed_colors() as $slug ) {
		printf(
			'<option value="%1$s"%3$s>%2$s</option>',
			esc_attr( $slug ),
			esc_html( $labels[ $slug ] ),
			selected( $current, $slug, false )
		);
	}
	echo '</select>';
	echo '<p class="description">' . esc_html__( 'El título solo se usa en el listado de administración; el texto visible es el del editor.', 'dw-tema' ) . '</p>';
}

/**
 * @param int $post_id Post ID.
 * @return void
 */
function doo_testimonio_save_meta( $post_id ) {
	if ( ! isset( $_POST['doo_testimonio_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['doo_testimonio_nonce'] ) ), 'doo_testimonio_save' ) ) {
		return;
	}
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	$key = doo_testimonio_color_meta_key();
	if ( isset( $_POST[ $key ] ) ) {
		$color = sanitize_text_field( wp_unslash( $_POST[ $key ] ) );
		if ( in_array( $color, doo_testimonio_allowed_colors(), true ) ) {
			update_post_meta( $post_id, $key, $color );
		}
	}
}
add_action( 'save_post_doo_testimonio', 'doo_testimonio_save_meta' );

/**
 * Default seed rows (color + quote text) — migración desde el bloque estático.
 *
 * @return array<int, array{color:string, text:string}>
 */
function doo_testimonios_seed_rows() {
	return array(
		array( 'color' => 'teal', 'text' => 'Se nota que el contenido del curso ha sido trabajado, analizado para conseguir los objetivos que se buscan. Creo que se debe, como he dicho antes, a un profesorado preparado pero sobre todo motivado y responsable con su labor. Estoy sorprendida gratamente porque no es lo habitual. Hay muchos cursos que se basan en corto y pego y ya. Muchas gracias.' ),
		array( 'color' => 'orange', 'text' => 'Felicitacions a la mestra! Es nota el domini de la matèria. Correcció molt ràpida y explicativa. Ha sigut un dels millors cursos que he fet de CDU. Gràcies!.' ),
		array( 'color' => 'navy', 'text' => 'Cada vez me gustan más los cursos que ofrecéis desde la Federación de Municipios. Que se puedan hacer de manera online nos facilita mucho la formación a aquellos que tenemos horarios complicados. Además los cursos específicos de mi puesto de trabajo que he podido realizar hasta ahora han sido muy útiles desde un punto de vista práctico y muy realistas en sus objetivos. ¡Felicidades y gracias!' ),
		array( 'color' => 'teal', 'text' => 'Professor té una gran quantitat de coneiximent i té molta capacitat de transmissió. A més, la pràctica ha sigut present en cadascuna de les jornades i amb molta capacitat. Molt recomanable.' ),
		array( 'color' => 'orange', 'text' => "El contingut del curs m'ha servit molt per enllaçar-lo en la feina que estic fent actualment." ),
		array( 'color' => 'navy', 'text' => 'Muy interesante y útil. La documentación y recursos que nos han facilitado durante el curso son una herramienta muy necesaria para nuestro trabajo en el día a día de la tramitación electrónica. Muchas gracias.' ),
		array( 'color' => 'teal', 'text' => 'Agrair fer videoconferències amb el professor (no sol ser habitual).' ),
		array( 'color' => 'orange', 'text' => 'Tot correcte. Enhorabona per esta mena de cursos en línia, són un encert si treballes a temps complet.' ),
		array( 'color' => 'navy', 'text' => "Vull remarcar que aquesta formació m'ha resultat molt interessant per a la meua feina i puc aplicar molts dels coneixements adquirits. La professora era molt solvent." ),
		array( 'color' => 'teal', 'text' => 'El general muy satisfecho como de costumbre con los cursos ofertados por la FVMP, en términos de flexibilidad horaria, contenido, resultados y adecuación a mi puesto de trabajo. Siempre que pueda repetiré sin dudarlo. Gracias por su esfuerzo en mejorar cada año en la impartición de cursos.' ),
		array( 'color' => 'orange', 'text' => 'La mestra sempre ha estat molt pendent de tot, correguint les tasques ràpidament, acompanyant en el procés.' ),
		array( 'color' => 'navy', 'text' => 'Ninguna observación, los cursos, la dinámica de los mismos y el profesorado son excepcionales.' ),
		array( 'color' => 'teal', 'text' => "Vull destacar la gran qualitat que té el material facilitat en aquest curs, està molt treballat, té un caràcter molt pràctic i facilita enormement la comprensió de la llei. La meua enhorabona al tutor!!" ),
		array( 'color' => 'orange', 'text' => 'Me encanta que se puedan facilitar este tipo de cursos para poder continuar con nuestra formación, es de agradecer y una muy buena inversión en los trabajadores. Muchísimas gracias por vuestra labor.' ),
		array( 'color' => 'navy', 'text' => "El professorat a contestat ràpidament les meues preguntes. Agraïr com sempre l'oportunitat que hem donen per a formar-me i adquirir nous coneiximents." ),
		array( 'color' => 'teal', 'text' => 'Me ha parecido un curso muy nutrido e interesante. Os animo a seguir en la misma dirección dotándonos de herramientas para mantenernos formados y formadas y actualizados. Gracias.' ),
		array( 'color' => 'orange', 'text' => 'Como siempre no tengo ninguna queja al respecto en ningún aspecto del curso realizado, muy muy satisfecho.' ),
		array( 'color' => 'navy', 'text' => 'Es el primer curso que hago en esta plataforma y me ha gustado mucho. MUCHAS GRACIAS.' ),
		array( 'color' => 'teal', 'text' => "Gràcies per permetre amb aquesta formació online que pugam formar-nos els treballadors de l'Administració Local sense moure de casa." ),
	);
}

/**
 * Crea los testimonios por defecto una sola vez si el CPT está vacío.
 *
 * @return void
 */
function doo_maybe_seed_testimonios() {
	if ( ! is_admin() || ! current_user_can( 'edit_posts' ) ) {
		return;
	}
	if ( get_option( 'doo_testimonios_seeded' ) ) {
		return;
	}

	$existing = get_posts(
		array(
			'post_type'      => 'doo_testimonio',
			'post_status'    => 'any',
			'posts_per_page' => 1,
			'fields'         => 'ids',
		)
	);
	if ( ! empty( $existing ) ) {
		update_option( 'doo_testimonios_seeded', '1' );
		return;
	}

	$rows = doo_testimonios_seed_rows();
	$order = 0;
	foreach ( $rows as $row ) {
		++$order;
		$post_id = wp_insert_post(
			array(
				'post_type'    => 'doo_testimonio',
				'post_status'  => 'publish',
				'post_title'   => sprintf(
					/* translators: %d: order number */
					__( 'Testimonio %d', 'dw-tema' ),
					$order
				),
				'post_content' => $row['text'],
				'menu_order'   => $order,
			),
			true
		);
		if ( ! is_wp_error( $post_id ) && $post_id ) {
			update_post_meta( $post_id, doo_testimonio_color_meta_key(), $row['color'] );
		}
	}

	update_option( 'doo_testimonios_seeded', '1' );
}
add_action( 'admin_init', 'doo_maybe_seed_testimonios', 20 );
