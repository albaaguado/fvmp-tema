<?php
/**
 * FVMP Theme — Shortcodes de la home que leen las opciones editables.
 *
 * [doo_hero]        → sección hero
 * [doo_presentacion] → sección de presentación del Secretario General
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Devuelve las opciones de la home mezcladas con los valores por defecto.
 *
 * @return array
 */
function doo_get_home_opts() {
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

	return wp_parse_args( get_option( 'doo_home_options', array() ), $defaults );
}

// ==========================================================================
// [doo_hero]
// ==========================================================================

/**
 * Shortcode [doo_hero] — renderiza la sección hero con datos editables.
 *
 * @return string HTML.
 */
function doo_hero_shortcode() {
	$o = doo_get_home_opts();

	// Imagen: desde la biblioteca de medios si hay ID, si no, la del tema.
	if ( $o['hero_image_id'] ) {
		$hero_img_url = wp_get_attachment_image_url( $o['hero_image_id'], 'full' );
		$hero_img_alt = get_post_meta( $o['hero_image_id'], '_wp_attachment_image_alt', true );
	} else {
		$hero_img_url = DOO_THEME_URI . '/assets/images/hero-img.png';
		$hero_img_alt = '';
	}

	ob_start();
	?>
	<section class="doo-hero">

		<div class="doo-hero__left">
			<p class="doo-hero__eyebrow"><?php echo esc_html( $o['hero_eyebrow'] ); ?></p>
			<h1 class="doo-hero__title"><?php echo esc_html( $o['hero_title'] ); ?></h1>
			<p class="doo-hero__desc"><?php echo esc_html( $o['hero_desc'] ); ?></p>
			<a class="doo-hero__btn" href="<?php echo esc_url( $o['hero_btn_url'] ); ?>"><?php echo esc_html( $o['hero_btn_text'] ); ?></a>
		</div>

		<div class="doo-hero__photo">
			<img src="<?php echo esc_url( $hero_img_url ); ?>" alt="<?php echo esc_attr( $hero_img_alt ); ?>" aria-hidden="true" />
			<div class="doo-hero__gradient"></div>
		</div>

	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'doo_hero', 'doo_hero_shortcode' );

// ==========================================================================
// [doo_presentacion]
// ==========================================================================

/**
 * Shortcode [doo_presentacion] — renderiza la sección de presentación con datos editables.
 *
 * @return string HTML.
 */
function doo_presentacion_shortcode() {
	$o = doo_get_home_opts();

	// Imagen del responsable.
	if ( $o['pres_image_id'] ) {
		$pres_img_url = wp_get_attachment_image_url( $o['pres_image_id'], 'medium' );
		$pres_img_alt = get_post_meta( $o['pres_image_id'], '_wp_attachment_image_alt', true );
		if ( ! $pres_img_alt ) {
			$pres_img_alt = $o['pres_name'];
		}
	} else {
		$pres_img_url = DOO_THEME_URI . '/assets/images/miguel.png';
		$pres_img_alt = esc_attr( $o['pres_name'] );
	}

	ob_start();
	?>
	<section class="doo-presentacion">
		<div class="doo-presentacion__container">
			<div class="doo-presentacion__inner">

				<div class="doo-presentacion__left">
					<div class="doo-presentacion__photo">
						<img src="<?php echo esc_url( $pres_img_url ); ?>" alt="<?php echo esc_attr( $pres_img_alt ); ?>" />
					</div>
					<p class="doo-presentacion__name"><?php echo esc_html( $o['pres_name'] ); ?></p>
					<p class="doo-presentacion__role"><?php echo esc_html( $o['pres_role'] ); ?></p>
				</div>

				<div class="doo-presentacion__right">
					<p class="doo-presentacion__eyebrow"><?php echo esc_html( $o['pres_eyebrow'] ); ?></p>
					<h2 class="doo-presentacion__title"><?php echo esc_html( $o['pres_title'] ); ?></h2>
					<p class="doo-presentacion__body"><?php echo wp_kses_post( $o['pres_body'] ); ?></p>

					<?php if ( $o['pres_full'] ) : ?>
						<div class="doo-presentacion__full" aria-hidden="true">
							<?php echo wp_kses_post( $o['pres_full'] ); ?>
						</div>
						<a class="doo-presentacion__link" href="#" aria-expanded="false"><?php echo esc_html( $o['pres_link_text'] ); ?></a>
					<?php endif; ?>
				</div>

			</div>
		</div>
	</section>
	<?php
	return ob_get_clean();
}
add_shortcode( 'doo_presentacion', 'doo_presentacion_shortcode' );
