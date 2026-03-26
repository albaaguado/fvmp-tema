<?php
/**
 * FVMP Theme — Functions and definitions.
 *
 * @package DooFvmpTheme
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Define theme constants.
 */
define( 'DOO_THEME_VERSION', wp_get_theme()->get( 'Version' ) );
define( 'DOO_THEME_DIR', get_template_directory() );
define( 'DOO_THEME_URI', get_template_directory_uri() );

/**
 * Set DOO_VITE_DEV to true in wp-config.php to use Vite dev server:
 * define( 'DOO_VITE_DEV', true );
 */

/**
 * Check if Vite dev server is running.
 *
 * @return bool True if dev server is active.
 */
function doo_is_vite_dev() {
	return defined( 'DOO_VITE_DEV' ) && DOO_VITE_DEV;
}

/**
 * Get Vite manifest for production asset paths.
 *
 * @return array Parsed manifest data.
 */
function doo_get_vite_manifest() {
	$manifest_path = DOO_THEME_DIR . '/dist/.vite/manifest.json';

	if ( ! file_exists( $manifest_path ) ) {
		return array();
	}

	$content = file_get_contents( $manifest_path );

	if ( false === $content ) {
		return array();
	}

	return json_decode( $content, true );
}

/**
 * Enqueue theme assets via Vite.
 *
 * In development: loads from Vite dev server (HMR).
 * In production: loads compiled files from /dist/ using manifest.
 *
 * @return void
 */
function doo_enqueue_assets() {

	// --- Development mode: Vite dev server ---
	if ( doo_is_vite_dev() ) {

		// Vite client for HMR.
		wp_enqueue_script(
			'doo-vite-client',
			'http://localhost:5173/@vite/client',
			array(),
			null,
			false
		);

		// Main entry point (Vite serves SCSS through JS).
		wp_enqueue_script(
			'doo-main-script',
			'http://localhost:5173/src/js/main.js',
			array(),
			null,
			true
		);

		return;
	}

	// --- Production mode: compiled assets ---
	$manifest = doo_get_vite_manifest();

	if ( empty( $manifest ) ) {
		return;
	}

	// Enqueue compiled CSS.
	if ( isset( $manifest['src/js/main.js']['css'] ) ) {
		foreach ( $manifest['src/js/main.js']['css'] as $index => $css_file ) {
			wp_enqueue_style(
				'doo-main-style-' . $index,
				DOO_THEME_URI . '/dist/' . $css_file,
				array(),
				null
			);
		}
	}

	// Enqueue compiled JS.
	if ( isset( $manifest['src/js/main.js']['file'] ) ) {
		wp_enqueue_script(
			'doo-main-script',
			DOO_THEME_URI . '/dist/' . $manifest['src/js/main.js']['file'],
			array(),
			null,
			true
		);
	}
}
add_action( 'wp_enqueue_scripts', 'doo_enqueue_assets' );

/**
 * Add type="module" to Vite scripts.
 *
 * @param string $tag    Script HTML tag.
 * @param string $handle Script handle.
 * @return string Modified script tag.
 */
function doo_vite_script_type_module( $tag, $handle ) {
	if ( str_starts_with( $handle, 'doo-' ) ) {
		$tag = str_replace( '<script ', '<script type="module" ', $tag );
	}
	return $tag;
}
add_filter( 'script_loader_tag', 'doo_vite_script_type_module', 10, 2 );

/**
 * Enqueue base theme stylesheet for WP identification.
 *
 * @return void
 */
function doo_enqueue_base_style() {
	wp_enqueue_style(
		'doo-base-style',
		get_stylesheet_uri(),
		array(),
		DOO_THEME_VERSION
	);
}
add_action( 'wp_enqueue_scripts', 'doo_enqueue_base_style' );