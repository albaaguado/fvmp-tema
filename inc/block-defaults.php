<?php
/**
 * Defaults para bloques de la home (sincronizados con block.json).
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * HTML largo del mensaje expandible (presentación).
 *
 * @return string
 */
function doo_theme_presentacion_full_default() {
	static $cached = null;
	if ( null !== $cached ) {
		return $cached;
	}
	$path = DOO_THEME_DIR . '/inc/defaults/presentacion-full.html';
	$cached = file_exists( $path ) ? (string) file_get_contents( $path ) : '';
	return $cached;
}

/**
 * @return array<string, string|int>
 */
function doo_hero_block_defaults() {
	return array(
		'eyebrow'     => 'FEDERACIÓ VALENCIANA DE MUNICIPIS I PROVÍNCIES',
		'title'       => 'Formación de la Federación Valenciana de Municipios y Provincias',
		'description' => 'Más de 10.000 plazas formativas especializadas cada año para los empleados públicos de la Comunitat Valenciana.',
		'buttonText'  => 'Explorar la oferta formativa',
		'buttonUrl'   => '#',
		'imageId'     => 0,
		'imageUrl'    => '',
	);
}

/**
 * @return array<string, string|int>
 */
function doo_presentacion_block_defaults() {
	return array(
		'imageId'   => 0,
		'imageUrl'  => '',
		'name'      => 'Miguel Bailach Luengo',
		'role'      => 'Secretario General',
		'eyebrow'   => 'PRESENTACIÓN',
		'title'     => 'Tres décadas de compromiso con la formación pública',
		'body'      => 'Desde hace casi tres décadas, la Federación Valenciana de Municipios y Provincias mantiene un compromiso firme con la formación continua y especializada del personal al servicio de la administración local de la Comunitat Valenciana. Una apuesta iniciada en 1996 con una convicción clara: el progreso de nuestros municipios pasa necesariamente por contar con empleadas y empleados públicos bien formados, motivados y preparados para responder a las demandas de una ciudadanía cada vez más exigente y mejor informada.',
		'fullText'  => doo_theme_presentacion_full_default(),
		'linkText'  => 'Leer mensaje completo →',
	);
}
