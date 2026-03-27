<?php
/**
 * FVMP Theme — Página de opciones para el contenido de la home.
 *
 * Permite al cliente editar desde wp-admin el hero y la sección de presentación.
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// ==========================================================================
// Menú de administración
// ==========================================================================

/**
 * Registra la página de opciones en el menú de wp-admin.
 *
 * @return void
 */
function doo_home_options_menu() {
	add_menu_page(
		__( 'Contenido Home', 'dw-tema' ),
		__( 'Contenido Home', 'dw-tema' ),
		'manage_options',
		'doo-home-options',
		'doo_home_options_page',
		'dashicons-welcome-write-blog',
		25
	);
}
add_action( 'admin_menu', 'doo_home_options_menu' );

// ==========================================================================
// Encolar media uploader en la página de opciones
// ==========================================================================

/**
 * Encola el media uploader de WP y el JS inline para los campos de imagen.
 *
 * @param string $hook Nombre de la página actual del admin.
 * @return void
 */
function doo_home_options_enqueue( $hook ) {
	if ( 'toplevel_page_doo-home-options' !== $hook ) {
		return;
	}

	wp_enqueue_media();

	$inline_js = "
		(function($){
			$('.doo-media-btn').on('click', function(e){
				e.preventDefault();
				var btn    = $(this);
				var target = btn.data('target');
				var preview = btn.data('preview');

				var frame = wp.media({
					title: 'Seleccionar imagen',
					button: { text: 'Usar esta imagen' },
					multiple: false
				});

				frame.on('select', function(){
					var att = frame.state().get('selection').first().toJSON();
					$('#' + target).val(att.id);
					$('#' + preview).attr('src', att.url).show();
				});

				frame.open();
			});

			$('.doo-media-remove').on('click', function(e){
				e.preventDefault();
				var target  = $(this).data('target');
				var preview = $(this).data('preview');
				$('#' + target).val('');
				$('#' + preview).attr('src', '').hide();
			});
		})(jQuery);
	";

	wp_add_inline_script( 'jquery', $inline_js );
}
add_action( 'admin_enqueue_scripts', 'doo_home_options_enqueue' );

// ==========================================================================
// Guardar opciones
// ==========================================================================

/**
 * Procesa y guarda las opciones del formulario.
 *
 * @return void
 */
function doo_home_options_save() {
	if ( ! isset( $_POST['doo_home_nonce'] ) ) {
		return;
	}
	if ( ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['doo_home_nonce'] ) ), 'doo_home_save' ) ) {
		return;
	}
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$opts = array(
		// Hero
		'hero_eyebrow'   => sanitize_text_field( wp_unslash( $_POST['hero_eyebrow'] ?? '' ) ),
		'hero_title'     => sanitize_text_field( wp_unslash( $_POST['hero_title'] ?? '' ) ),
		'hero_desc'      => sanitize_text_field( wp_unslash( $_POST['hero_desc'] ?? '' ) ),
		'hero_btn_text'  => sanitize_text_field( wp_unslash( $_POST['hero_btn_text'] ?? '' ) ),
		'hero_btn_url'   => esc_url_raw( wp_unslash( $_POST['hero_btn_url'] ?? '' ) ),
		'hero_image_id'  => absint( $_POST['hero_image_id'] ?? 0 ),

		// Presentación
		'pres_image_id'  => absint( $_POST['pres_image_id'] ?? 0 ),
		'pres_name'      => sanitize_text_field( wp_unslash( $_POST['pres_name'] ?? '' ) ),
		'pres_role'      => sanitize_text_field( wp_unslash( $_POST['pres_role'] ?? '' ) ),
		'pres_eyebrow'   => sanitize_text_field( wp_unslash( $_POST['pres_eyebrow'] ?? '' ) ),
		'pres_title'     => sanitize_text_field( wp_unslash( $_POST['pres_title'] ?? '' ) ),
		'pres_body'      => wp_kses_post( wp_unslash( $_POST['pres_body'] ?? '' ) ),
		'pres_full'      => wp_kses_post( wp_unslash( $_POST['pres_full'] ?? '' ) ),
		'pres_link_text' => sanitize_text_field( wp_unslash( $_POST['pres_link_text'] ?? '' ) ),
	);

	update_option( 'doo_home_options', $opts );
}

// ==========================================================================
// Render de la página de opciones
// ==========================================================================

/**
 * Renderiza la página de opciones "Contenido Home".
 *
 * @return void
 */
function doo_home_options_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}

	$saved = false;
	if ( isset( $_POST['doo_home_nonce'] ) ) {
		doo_home_options_save();
		$saved = true;
	}

	$opts = get_option( 'doo_home_options', array() );

	// Defaults para la primera vez (los valores que están hardcodeados en el HTML).
	$defaults = array(
		'hero_eyebrow'   => 'FEDERACIÓ VALENCIANA DE MUNICIPIS I PROVÍNCIES',
		'hero_title'     => 'Formación de la Federación Valenciana de Municipios y Provincias',
		'hero_desc'      => 'Más de 10.000 plazas formativas especializadas cada año para los empleados públicos de la Comunitat Valenciana.',
		'hero_btn_text'  => 'Explorar la oferta formativa',
		'hero_btn_url'   => '#',
		'hero_image_id'  => 0,
		'pres_image_id'  => 0,
		'pres_name'      => 'Miguel Bailach Luengo',
		'pres_role'      => 'Secretario General',
		'pres_eyebrow'   => 'PRESENTACIÓN',
		'pres_title'     => 'Tres décadas de compromiso con la formación pública',
		'pres_body'      => 'Desde hace casi tres décadas, la Federación Valenciana de Municipios y Provincias mantiene un compromiso firme con la formación continua y especializada del personal al servicio de la administración local de la Comunitat Valenciana. Una apuesta iniciada en 1996 con una convicción clara: el progreso de nuestros municipios pasa necesariamente por contar con empleadas y empleados públicos bien formados, motivados y preparados para responder a las demandas de una ciudadanía cada vez más exigente y mejor informada.',
		'pres_full'      => '<p>En 1996 la FVMP apostó por la formación continua y especializada de las empleadas y empleados públicos de la administración local de la CV como motor de progreso de nuestros municipios, imprescindible para ofrecer unos servicios públicos de calidad a una ciudadanía cada vez más preparada. Después de 29 años podemos decir que esa apuesta fue todo un acierto: más de 40.000 trabajadoras y trabajadores públicos se han formado con nuestros cursos en los últimos 8 años, mostrando un nivel de satisfacción con la formación realizada cercano al 98% y llevando a la práctica -en su mayoría- los conocimientos adquiridos.</p><p>Tras la buena acogida de la formación ofertada el año pasado (recibiendo más de 30.000 solicitudes para los diferentes planes ofertados), en 2025 hemos hecho un gran esfuerzo por mejorar esa oferta teniendo en cuenta muchas de las propuestas que nos hacéis llegar a través de los cuestionarios anónimos de satisfacción.</p><p>El plan de Formación Continua (FC) está dirigido a todos los empleados públicos de la administración local, y en él tienen cabida acciones formativas de diferentes áreas (administración electrónica, jurídico-procedimental, urbanismo, específicas para determinados colectivos, etc.) para todos los niveles y perfiles laborales, con el objetivo de fortalecer vuestras habilidades y proporcionaros conocimientos actualizados en cualquier materia que sea de vuestro interés, dando prioridad a las últimas novedades tecnológicas y legislativas.</p><p>La formación para equipos de Atención Primaria (FAP) está dirigida exclusivamente a equipos de atención primaria básica y específica de la administración local, y se ha desarrollado con la inestimable ayuda de la Conselleria de Servicios Sociales, Igualdad y Vivienda, y sus contenidos cubren todas las áreas en las que estos equipos prestan y son necesarios sus servicios (atención primaria, mujer y violencia de género, diversidad, familia -infancia y adolescencia-, mayores y dependencia, inclusión y cooperación…).</p><p>Por último tenemos el Programa de Innovación Municipal (PIM), plan de formación dirigido a todos los empleados públicos de la administración local cuyo principal objetivo es el de fomentar la INNOVACIÓN como una alternativa para realizar de forma más eficiente vuestras tareas diarias e incluso prever otras que serán necesarias en un futuro inmediato, con cursos transversales de aplicación a cualquier puesto de trabajo y otros más específicos para determinadas áreas. Este plan es consecuencia de la colaboración con la Conselleria de Innovación, Industria, Comercio y Turismo, y cuenta con la participación de la Universidad de Alicante, la Universidad Jaime I de Castellón y la Universidad de Valencia.</p><p>No podemos olvidarnos de la formación de nuestros cargos electos, con una serie de acciones específicas cuyo objetivo es mejorar sus aptitudes al frente de sus respectivas concejalías y directamente con la ciudadanía.</p><p>Solo me queda agradeceros la confianza que año tras año depositáis en nuestros cursos para formaros, nuestro único objetivo es fortalecer vuestra capacidad personal y profesional y que ello repercuta positivamente en vuestros municipios.</p>',
		'pres_link_text' => 'Leer mensaje completo →',
	);

	$o = wp_parse_args( $opts, $defaults );

	// URLs de previsualización de imágenes.
	$hero_img_url = $o['hero_image_id'] ? wp_get_attachment_image_url( $o['hero_image_id'], 'medium' ) : '';
	$pres_img_url = $o['pres_image_id'] ? wp_get_attachment_image_url( $o['pres_image_id'], 'medium' ) : '';

	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Contenido Home', 'dw-tema' ); ?></h1>

		<?php if ( $saved ) : ?>
			<div class="notice notice-success is-dismissible"><p><?php esc_html_e( 'Cambios guardados correctamente.', 'dw-tema' ); ?></p></div>
		<?php endif; ?>

		<form method="post">
			<?php wp_nonce_field( 'doo_home_save', 'doo_home_nonce' ); ?>

			<!-- ============================================================ -->
			<!-- HERO                                                          -->
			<!-- ============================================================ -->
			<h2 style="border-bottom:1px solid #ddd;padding-bottom:8px;margin-top:32px;"><?php esc_html_e( 'Hero', 'dw-tema' ); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>

					<tr>
						<th scope="row"><label for="hero_eyebrow"><?php esc_html_e( 'Supratítulo', 'dw-tema' ); ?></label></th>
						<td><input type="text" id="hero_eyebrow" name="hero_eyebrow" value="<?php echo esc_attr( $o['hero_eyebrow'] ); ?>" class="regular-text" /></td>
					</tr>

					<tr>
						<th scope="row"><label for="hero_title"><?php esc_html_e( 'Título principal', 'dw-tema' ); ?></label></th>
						<td><input type="text" id="hero_title" name="hero_title" value="<?php echo esc_attr( $o['hero_title'] ); ?>" class="large-text" /></td>
					</tr>

					<tr>
						<th scope="row"><label for="hero_desc"><?php esc_html_e( 'Descripción', 'dw-tema' ); ?></label></th>
						<td><textarea id="hero_desc" name="hero_desc" rows="3" class="large-text"><?php echo esc_textarea( $o['hero_desc'] ); ?></textarea></td>
					</tr>

					<tr>
						<th scope="row"><label for="hero_btn_text"><?php esc_html_e( 'Texto del botón', 'dw-tema' ); ?></label></th>
						<td><input type="text" id="hero_btn_text" name="hero_btn_text" value="<?php echo esc_attr( $o['hero_btn_text'] ); ?>" class="regular-text" /></td>
					</tr>

					<tr>
						<th scope="row"><label for="hero_btn_url"><?php esc_html_e( 'URL del botón', 'dw-tema' ); ?></label></th>
						<td><input type="url" id="hero_btn_url" name="hero_btn_url" value="<?php echo esc_url( $o['hero_btn_url'] ); ?>" class="regular-text" /></td>
					</tr>

					<tr>
						<th scope="row"><?php esc_html_e( 'Imagen hero', 'dw-tema' ); ?></th>
						<td>
							<input type="hidden" id="hero_image_id" name="hero_image_id" value="<?php echo esc_attr( $o['hero_image_id'] ); ?>" />
							<div style="margin-bottom:8px;">
								<img id="hero_image_preview" src="<?php echo esc_url( $hero_img_url ); ?>" style="max-width:240px;max-height:120px;display:<?php echo $hero_img_url ? 'block' : 'none'; ?>;" />
							</div>
							<button type="button" class="button doo-media-btn" data-target="hero_image_id" data-preview="hero_image_preview">
								<?php esc_html_e( 'Seleccionar imagen', 'dw-tema' ); ?>
							</button>
							<?php if ( $hero_img_url ) : ?>
								<button type="button" class="button doo-media-remove" data-target="hero_image_id" data-preview="hero_image_preview" style="margin-left:6px;">
									<?php esc_html_e( 'Eliminar', 'dw-tema' ); ?>
								</button>
							<?php endif; ?>
							<p class="description"><?php esc_html_e( 'Si no se selecciona ninguna imagen se usará la imagen por defecto del tema.', 'dw-tema' ); ?></p>
						</td>
					</tr>

				</tbody>
			</table>

			<!-- ============================================================ -->
			<!-- PRESENTACIÓN                                                   -->
			<!-- ============================================================ -->
			<h2 style="border-bottom:1px solid #ddd;padding-bottom:8px;margin-top:40px;"><?php esc_html_e( 'Presentación', 'dw-tema' ); ?></h2>
			<table class="form-table" role="presentation">
				<tbody>

					<tr>
						<th scope="row"><?php esc_html_e( 'Foto del responsable', 'dw-tema' ); ?></th>
						<td>
							<input type="hidden" id="pres_image_id" name="pres_image_id" value="<?php echo esc_attr( $o['pres_image_id'] ); ?>" />
							<div style="margin-bottom:8px;">
								<img id="pres_image_preview" src="<?php echo esc_url( $pres_img_url ); ?>" style="max-width:160px;max-height:160px;border-radius:50%;display:<?php echo $pres_img_url ? 'block' : 'none'; ?>;" />
							</div>
							<button type="button" class="button doo-media-btn" data-target="pres_image_id" data-preview="pres_image_preview">
								<?php esc_html_e( 'Seleccionar imagen', 'dw-tema' ); ?>
							</button>
							<?php if ( $pres_img_url ) : ?>
								<button type="button" class="button doo-media-remove" data-target="pres_image_id" data-preview="pres_image_preview" style="margin-left:6px;">
									<?php esc_html_e( 'Eliminar', 'dw-tema' ); ?>
								</button>
							<?php endif; ?>
							<p class="description"><?php esc_html_e( 'Si no se selecciona ninguna imagen se usará la imagen por defecto del tema.', 'dw-tema' ); ?></p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="pres_name"><?php esc_html_e( 'Nombre', 'dw-tema' ); ?></label></th>
						<td><input type="text" id="pres_name" name="pres_name" value="<?php echo esc_attr( $o['pres_name'] ); ?>" class="regular-text" /></td>
					</tr>

					<tr>
						<th scope="row"><label for="pres_role"><?php esc_html_e( 'Cargo', 'dw-tema' ); ?></label></th>
						<td><input type="text" id="pres_role" name="pres_role" value="<?php echo esc_attr( $o['pres_role'] ); ?>" class="regular-text" /></td>
					</tr>

					<tr>
						<th scope="row"><label for="pres_eyebrow"><?php esc_html_e( 'Supratítulo', 'dw-tema' ); ?></label></th>
						<td><input type="text" id="pres_eyebrow" name="pres_eyebrow" value="<?php echo esc_attr( $o['pres_eyebrow'] ); ?>" class="regular-text" /></td>
					</tr>

					<tr>
						<th scope="row"><label for="pres_title"><?php esc_html_e( 'Título', 'dw-tema' ); ?></label></th>
						<td><input type="text" id="pres_title" name="pres_title" value="<?php echo esc_attr( $o['pres_title'] ); ?>" class="large-text" /></td>
					</tr>

					<tr>
						<th scope="row"><label for="pres_body"><?php esc_html_e( 'Texto introductorio', 'dw-tema' ); ?></label></th>
						<td>
							<textarea id="pres_body" name="pres_body" rows="5" class="large-text"><?php echo esc_textarea( $o['pres_body'] ); ?></textarea>
							<p class="description"><?php esc_html_e( 'Párrafo visible por defecto antes de expandir.', 'dw-tema' ); ?></p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="pres_full"><?php esc_html_e( 'Mensaje completo', 'dw-tema' ); ?></label></th>
						<td>
							<textarea id="pres_full" name="pres_full" rows="14" class="large-text"><?php echo esc_textarea( $o['pres_full'] ); ?></textarea>
							<p class="description"><?php esc_html_e( 'Texto que se muestra al pulsar "Leer mensaje completo". Se puede usar HTML básico (<p>, <strong>, etc.).', 'dw-tema' ); ?></p>
						</td>
					</tr>

					<tr>
						<th scope="row"><label for="pres_link_text"><?php esc_html_e( 'Texto del enlace "Leer más"', 'dw-tema' ); ?></label></th>
						<td><input type="text" id="pres_link_text" name="pres_link_text" value="<?php echo esc_attr( $o['pres_link_text'] ); ?>" class="regular-text" /></td>
					</tr>

				</tbody>
			</table>

			<?php submit_button( __( 'Guardar cambios', 'dw-tema' ) ); ?>
		</form>
	</div>
	<?php
}
